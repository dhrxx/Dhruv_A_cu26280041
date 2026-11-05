<?php
// Implement a wishlist feature allowing users to save books to a personal wishlist stored in the database.
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php';
$user = require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $bookId = filter_var($_POST['book_id'] ?? null, FILTER_VALIDATE_INT);
    if (!$bookId) {
        http_response_code(400);
        exit('A valid book ID is required.');
    }
    $book = db()->prepare('SELECT id FROM books WHERE id = :id');
    $book->execute(['id' => $bookId]);
    if ($book->fetchColumn() === false) {
        http_response_code(404);
        exit('Book not found.');
    }
    $save = db()->prepare(
        'INSERT INTO wishlist (user_id, book_id) VALUES (:user_id, :book_id)
         ON DUPLICATE KEY UPDATE created_at = CURRENT_TIMESTAMP'
    );
    $save->execute(['user_id' => $user['id'], 'book_id' => $bookId]);
    set_flash('Book saved to your wishlist.');
    redirect('17_wishlist.php');
}

$items = db()->prepare(
    'SELECT books.id, books.title, books.author, books.category, books.price, books.stock, books.image
     FROM wishlist JOIN books ON books.id = wishlist.book_id
     WHERE wishlist.user_id = :user_id ORDER BY wishlist.created_at DESC'
);
$items->execute(['user_id' => $user['id']]);
$books = $items->fetchAll();
bookstore_header('Your wishlist');
?>
<div class="book-grid"><?php foreach ($books as $book): book_card($book); endforeach; ?>
<?php if ($books === []): ?><p>Your wishlist is empty. <a href="03_books.php">Explore books</a>.</p><?php endif; ?>
</div>
<?php bookstore_footer(); ?>
