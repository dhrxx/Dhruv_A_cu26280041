<?php
// Incorporate a search and filter feature in PHP allowing users to search books by title, author, or category.
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php';
$search = trim((string) ($_GET['q'] ?? ''));
$category = trim((string) ($_GET['category'] ?? ''));
$categoryQuery = db()->query('SELECT DISTINCT category FROM books ORDER BY category');
$categories = $categoryQuery->fetchAll(PDO::FETCH_COLUMN);
$sql = 'SELECT id, title, author, category, price, stock, image FROM books WHERE stock > 0';
$parameters = [];
if ($search !== '') {
    $sql .= ' AND (title LIKE :title OR author LIKE :author OR category LIKE :category_search)';
    $term = '%' . $search . '%';
    $parameters['title'] = $term;
    $parameters['author'] = $term;
    $parameters['category_search'] = $term;
}
if ($category !== '' && in_array($category, $categories, true)) {
    $sql .= ' AND category = :selected_category';
    $parameters['selected_category'] = $category;
}
$sql .= ' ORDER BY title';
$statement = db()->prepare($sql);
$statement->execute($parameters);
$books = $statement->fetchAll();
bookstore_header('Search books');
?>
<form method="get" class="search-form">
    <label>Title, author, or category <input type="search" name="q" value="<?= e($search) ?>" maxlength="100"></label>
    <label>Category <select name="category"><option value="">All categories</option>
        <?php foreach ($categories as $option): ?><option value="<?= e($option) ?>"<?= $option === $category ? ' selected' : '' ?>><?= e($option) ?></option><?php endforeach; ?>
    </select></label>
    <button type="submit">Filter books</button>
</form>
<div class="book-grid"><?php foreach ($books as $book): book_card($book); endforeach; ?>
<?php if ($books === []): ?><p>No books matched that search.</p><?php endif; ?></div>
<?php bookstore_footer(); ?>
