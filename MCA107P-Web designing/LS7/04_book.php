<?php
// Build a book detail page in PHP that retrieves a specific book's information using its ID passed via URL (`GET`).
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$statement = db()->prepare(
    'SELECT id, title, author, category, description, price, stock, image
     FROM books WHERE id = :id'
);
$statement->execute(['id' => $id ?: 0]);
$book = $statement->fetch();
if (!$book) {
    http_response_code(404);
}
bookstore_header($book ? $book['title'] : 'Book not found');
if ($book):
?>
<article class="book-detail">
    <?php if ($book['image']): ?><img src="<?= e($book['image']) ?>" alt="Cover of <?= e($book['title']) ?>"><?php endif; ?>
    <p class="eyebrow"><?= e($book['category']) ?></p>
    <p>by <?= e($book['author']) ?></p>
    <p class="price">$<?= number_format((float) $book['price'], 2) ?></p>
    <p><?= nl2br(e($book['description'])) ?></p>
    <p><?= (int) $book['stock'] > 0 ? 'In stock: ' . (int) $book['stock'] : 'Currently out of stock' ?></p>
    <?php book_card(array_merge($book, ['category' => $book['category']])); ?>
    <form method="post" action="17_wishlist.php"><?= csrf_field() ?>
        <input type="hidden" name="book_id" value="<?= (int) $book['id'] ?>">
        <button type="submit">Save to wishlist</button>
    </form>
</article>
<?php require __DIR__ . '/15_reviews.php'; ?>
<?php else: ?>
<p>That book could not be found. <a href="03_books.php">Browse the catalogue</a>.</p>
<?php endif; ?>
<?php bookstore_footer(); ?>
