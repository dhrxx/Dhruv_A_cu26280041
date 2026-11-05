<?php
// Implement secure input sanitization and prepared statements across all bookstore database queries to prevent SQL injection.
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php';
require_admin();
$errors = [];
$saved = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $title = trim((string) ($_POST['title'] ?? ''));
    $author = trim((string) ($_POST['author'] ?? ''));
    $category = trim((string) ($_POST['category'] ?? ''));
    $description = trim((string) ($_POST['description'] ?? ''));
    $price = filter_var($_POST['price'] ?? null, FILTER_VALIDATE_FLOAT);
    $stock = filter_var($_POST['stock'] ?? null, FILTER_VALIDATE_INT);

    if ($title === '' || strlen($title) > 200) $errors[] = 'Title is required and limited to 200 characters.';
    if ($author === '' || strlen($author) > 160) $errors[] = 'Author is required and limited to 160 characters.';
    if ($category === '' || strlen($category) > 100) $errors[] = 'Category is required and limited to 100 characters.';
    if ($price === false || $price < 0) $errors[] = 'Price must be a non-negative number.';
    if ($stock === false || $stock < 0) $errors[] = 'Stock must be a non-negative whole number.';

    if ($errors === []) {
        $statement = db()->prepare(
            'INSERT INTO books (title, author, category, description, price, stock)
             VALUES (:title, :author, :category, :description, :price, :stock)'
        );
        $statement->execute([
            'title' => $title, 'author' => $author, 'category' => $category,
            'description' => $description, 'price' => $price, 'stock' => $stock,
        ]);
        set_flash('Validated book saved with a parameterized query.');
        redirect('14_secure_queries.php');
    }
}

bookstore_header('Validated catalogue entry');
foreach ($errors as $error): ?><p class="error"><?= e($error) ?></p><?php endforeach; ?>
<p>Inputs are validated before writing. Values are bound as parameters; HTML output is escaped separately.</p>
<form method="post" data-validate><?= csrf_field() ?>
    <label>Title <input name="title" maxlength="200" required></label>
    <label>Author <input name="author" maxlength="160" required></label>
    <label>Category <input name="category" maxlength="100" required></label>
    <label>Description <textarea name="description"></textarea></label>
    <label>Price <input type="number" name="price" min="0" step="0.01" required></label>
    <label>Stock <input type="number" name="stock" min="0" required></label>
    <button type="submit">Save validated book</button>
</form>
<?php bookstore_footer(); ?>
