<?php
// Develop a PHP script to fetch and display available books dynamically from the MySQL database in a grid layout.
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php';
$books = db()->query(
    'SELECT id, title, author, category, price, stock, image
     FROM books WHERE stock > 0 ORDER BY title'
)->fetchAll();
bookstore_header('Available books');
?>
<div class="book-grid">
    <?php foreach ($books as $book): book_card($book); endforeach; ?>
    <?php if ($books === []): ?><p>No in-stock books are available yet.</p><?php endif; ?>
</div>
<?php bookstore_footer(); ?>
