<?php
// Build an inventory stock check in PHP that prevents users from adding more books to the cart than available stock.
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('03_books.php');
}
verify_csrf();
$bookId = filter_var($_POST['book_id'] ?? null, FILTER_VALIDATE_INT);
$quantity = filter_var($_POST['quantity'] ?? null, FILTER_VALIDATE_INT);
if (!$bookId || !$quantity || $quantity < 1 || $quantity > 99) {
    set_flash('Choose a valid book and quantity.');
    redirect('03_books.php');
}

$statement = db()->prepare('SELECT title, stock FROM books WHERE id = :id');
$statement->execute(['id' => $bookId]);
$book = $statement->fetch();
if (!$book) {
    http_response_code(404);
    exit('Book not found.');
}
$alreadyInCart = (int) ($_SESSION['cart'][$bookId] ?? 0);
if ($alreadyInCart + $quantity > (int) $book['stock']) {
    set_flash('Only ' . (int) $book['stock'] . ' copies of ' . $book['title'] . ' are in stock.');
} else {
    $_SESSION['cart'][$bookId] = $alreadyInCart + $quantity;
    set_flash('Book added to your cart.');
}
redirect('06_cart.php');
