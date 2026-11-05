<?php
// Implement an admin dashboard in PHP allowing administrators to add new books, update prices, and manage inventory levels.
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php';
require_admin();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = (string) ($_POST['action'] ?? '');
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
    if ($action === 'add') {
        $title = trim((string) ($_POST['title'] ?? ''));
        $author = trim((string) ($_POST['author'] ?? ''));
        $category = trim((string) ($_POST['category'] ?? 'General'));
        $price = filter_var($_POST['price'] ?? null, FILTER_VALIDATE_FLOAT);
        $stock = filter_var($_POST['stock'] ?? null, FILTER_VALIDATE_INT);
        if ($title === '' || strlen($title) > 200 || $author === '' || strlen($author) > 160
            || $category === '' || strlen($category) > 100 || $price === false || $price < 0
            || $stock === false || $stock < 0) {
            $error = 'Enter a title, author, category, non-negative price, and non-negative stock.';
        } else {
            $insert = db()->prepare(
                'INSERT INTO books (title, author, category, description, price, stock, image)
                 VALUES (:title, :author, :category, :description, :price, :stock, NULL)'
            );
            $insert->execute([
                'title' => $title, 'author' => $author, 'category' => $category,
                'description' => trim((string) ($_POST['description'] ?? '')),
                'price' => $price, 'stock' => $stock,
            ]);
            set_flash('Book added to the catalogue.');
            redirect('08_admin.php');
        }
    } elseif ($action === 'update' && $id) {
        $price = filter_var($_POST['price'] ?? null, FILTER_VALIDATE_FLOAT);
        $stock = filter_var($_POST['stock'] ?? null, FILTER_VALIDATE_INT);
        if ($price === false || $price < 0 || $stock === false || $stock < 0) {
            $error = 'Price and stock must be zero or greater.';
        } else {
            $update = db()->prepare('UPDATE books SET price = :price, stock = :stock WHERE id = :id');
            $update->execute(['price' => $price, 'stock' => $stock, 'id' => $id]);
            set_flash('Book price and inventory updated.');
            redirect('08_admin.php');
        }
    }
}

$books = db()->query('SELECT id, title, author, category, price, stock FROM books ORDER BY title')->fetchAll();
bookstore_header('Bookstore administration');
if ($error !== ''): ?><p class="error" role="alert"><?= e($error) ?></p><?php endif; ?>
<section>
    <h2>Add a book</h2>
    <form method="post" data-validate><?= csrf_field() ?><input type="hidden" name="action" value="add">
        <label>Title <input name="title" maxlength="200" required></label>
        <label>Author <input name="author" maxlength="160" required></label>
        <label>Category <input name="category" maxlength="100" required value="General"></label>
        <label>Description <textarea name="description" rows="3"></textarea></label>
        <label>Price <input type="number" name="price" min="0" step="0.01" required></label>
        <label>Stock <input type="number" name="stock" min="0" step="1" required></label>
        <button type="submit">Add book</button>
    </form>
</section>
<section>
    <h2>Manage books</h2>
    <div class="table-wrap"><table><thead><tr><th>Title</th><th>Author</th><th>Category</th><th>Price</th><th>Stock</th><th></th></tr></thead><tbody>
    <?php foreach ($books as $book): ?><tr><td><?= e($book['title']) ?></td><td><?= e($book['author']) ?></td><td><?= e($book['category']) ?></td>
        <td colspan="3"><form method="post" class="inline-form"><?= csrf_field() ?><input type="hidden" name="action" value="update"><input type="hidden" name="id" value="<?= (int) $book['id'] ?>">
        <label class="sr-only" for="price-<?= (int) $book['id'] ?>">Price</label><input id="price-<?= (int) $book['id'] ?>" type="number" name="price" min="0" step="0.01" value="<?= e($book['price']) ?>" required>
        <label class="sr-only" for="stock-<?= (int) $book['id'] ?>">Stock</label><input id="stock-<?= (int) $book['id'] ?>" type="number" name="stock" min="0" value="<?= (int) $book['stock'] ?>" required>
        <button>Save</button></form></td></tr><?php endforeach; ?>
    </tbody></table></div>
    <p><a href="09_admin_orders.php">Manage customer orders</a> · <a href="21_sales_report.php">View sales reports</a></p>
</section>
<?php bookstore_footer(); ?>
