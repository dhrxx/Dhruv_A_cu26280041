<?php
// Implement pagination in PHP to display a limited number of books per page with page number links.
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php';
$perPage = 8;
$requestedPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
$page = max(1, (int) ($requestedPage ?: 1));
$total = (int) db()->query('SELECT COUNT(*) FROM books WHERE stock > 0')->fetchColumn();
$pages = max(1, (int) ceil($total / $perPage));
$page = min($page, $pages);
$offset = ($page - 1) * $perPage;
$statement = db()->prepare(
    'SELECT id, title, author, category, price, stock, image FROM books
     WHERE stock > 0 ORDER BY title LIMIT :limit OFFSET :offset'
);
$statement->bindValue('limit', $perPage, PDO::PARAM_INT);
$statement->bindValue('offset', $offset, PDO::PARAM_INT);
$statement->execute();
$books = $statement->fetchAll();
bookstore_header('Browse the catalogue');
?>
<p>Page <?= $page ?> of <?= $pages ?> · <?= $total ?> available books</p>
<div class="book-grid"><?php foreach ($books as $book): book_card($book); endforeach; ?>
<?php if ($books === []): ?><p>No books are available on this page.</p><?php endif; ?></div>
<nav class="pagination" aria-label="Book pages">
    <?php if ($page > 1): ?><a href="?page=<?= $page - 1 ?>">Previous</a><?php endif; ?>
    <?php for ($number = 1; $number <= $pages; $number++): ?>
        <a<?= $number === $page ? ' aria-current="page"' : '' ?> href="?page=<?= $number ?>"><?= $number ?></a>
    <?php endfor; ?>
    <?php if ($page < $pages): ?><a href="?page=<?= $page + 1 ?>">Next</a><?php endif; ?>
</nav>
<?php bookstore_footer(); ?>
