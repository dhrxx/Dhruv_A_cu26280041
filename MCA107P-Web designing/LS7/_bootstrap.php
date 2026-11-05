<?php
declare(strict_types=1);

$secureCookie = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
session_set_cookie_params([
    'httponly' => true,
    'secure' => $secureCookie,
    'samesite' => 'Lax',
]);
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function db(): PDO
{
    static $pdo;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = getenv('BOOKSTORE_DSN') ?: 'mysql:host=127.0.0.1;dbname=online_bookstore;charset=utf8mb4';
    $user = getenv('BOOKSTORE_DB_USER');
    if ($user === false || $user === '') {
        throw new RuntimeException('Set BOOKSTORE_DB_USER before connecting to the bookstore database.');
    }
    $password = getenv('BOOKSTORE_DB_PASSWORD');
    $pdo = new PDO($dsn, $user, $password === false ? '' : $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $location): never
{
    header('Location: ' . $location, true, 303);
    exit;
}

function csrf_token(): string
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void
{
    $submitted = $_POST['csrf_token'] ?? '';
    if (!is_string($submitted) || !hash_equals(csrf_token(), $submitted)) {
        http_response_code(400);
        exit('Invalid form token. Go back, reload the form, and try again.');
    }
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function require_login(): array
{
    $user = current_user();
    if ($user === null) {
        redirect('05_auth.php');
    }
    return $user;
}

function require_admin(): array
{
    $user = require_login();
    if (($user['role'] ?? '') !== 'admin') {
        http_response_code(403);
        exit('Administrator access required.');
    }
    return $user;
}

function place_order(PDO $pdo, int $userId, array $cart, array $shipping): int
{
    if ($userId < 1) {
        throw new DomainException('A signed-in customer is required to place an order.');
    }
    if ($cart === []) {
        throw new DomainException('Your cart is empty.');
    }
    $fieldLimits = ['name' => 120, 'address' => 255, 'city' => 120, 'postal_code' => 24];
    foreach ($fieldLimits as $field => $limit) {
        if (!isset($shipping[$field]) || trim((string) $shipping[$field]) === ''
            || strlen((string) $shipping[$field]) > $limit) {
            throw new DomainException('Complete all shipping fields using the allowed length.');
        }
    }

    $ownsTransaction = !$pdo->inTransaction();
    if ($ownsTransaction) {
        $pdo->beginTransaction();
    }
    try {
        $orderRows = [];
        $totalCents = 0;
        $selectBook = $pdo->prepare('SELECT id, title, price, stock FROM books WHERE id = :id FOR UPDATE');
        foreach ($cart as $bookId => $quantity) {
            $quantity = filter_var($quantity, FILTER_VALIDATE_INT);
            if (!$quantity || $quantity < 1) {
                throw new DomainException('The cart contains an invalid quantity.');
            }
            $selectBook->execute(['id' => (int) $bookId]);
            $book = $selectBook->fetch();
            if (!$book || $quantity > (int) $book['stock']) {
                throw new DomainException('A requested book is no longer available in that quantity.');
            }
            $totalCents += (int) round((float) $book['price'] * 100) * $quantity;
            $orderRows[] = [$book, $quantity];
        }
        $total = intdiv($totalCents, 100) . '.' . str_pad((string) ($totalCents % 100), 2, '0', STR_PAD_LEFT);

        $createOrder = $pdo->prepare(
            'INSERT INTO orders (user_id, shipping_name, shipping_address, shipping_city, shipping_postal_code, total)
             VALUES (:user_id, :name, :address, :city, :postal_code, :total)'
        );
        $createOrder->execute([
            'user_id' => $userId, 'name' => $shipping['name'], 'address' => $shipping['address'],
            'city' => $shipping['city'], 'postal_code' => $shipping['postal_code'], 'total' => $total,
        ]);
        $orderId = (int) $pdo->lastInsertId();
        $insertItem = $pdo->prepare(
            'INSERT INTO order_items (order_id, book_id, title_at_purchase, quantity, unit_price)
             VALUES (:order_id, :book_id, :title, :quantity, :price)'
        );
        $decreaseStock = $pdo->prepare('UPDATE books SET stock = stock - :quantity WHERE id = :id');
        foreach ($orderRows as [$book, $quantity]) {
            $insertItem->execute([
                'order_id' => $orderId, 'book_id' => $book['id'], 'title' => $book['title'],
                'quantity' => $quantity, 'price' => $book['price'],
            ]);
            $decreaseStock->execute(['quantity' => $quantity, 'id' => $book['id']]);
        }
        if ($ownsTransaction) {
            $pdo->commit();
        }
        return $orderId;
    } catch (Throwable $exception) {
        if ($ownsTransaction && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $exception;
    }
}

function set_flash(string $message): void
{
    $_SESSION['flash'] = $message;
}

function take_flash(): ?string
{
    $message = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $message;
}

function bookstore_header(string $title): void
{
    $user = current_user();
    $flash = take_flash();
    echo '<!doctype html><html lang="en"><head><meta charset="utf-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>' . e($title) . ' | Chapter &amp; Co.</title>';
    echo '<link rel="stylesheet" href="19_assets.css"><script src="18_validation.js" defer></script></head><body>';
    echo '<header class="site-header"><a class="brand" href="01_home.php">Chapter &amp; Co.</a>';
    echo '<nav aria-label="Main navigation"><a href="03_books.php">Books</a> ';
    echo '<a href="06_cart.php">Cart (' . (int) array_sum($_SESSION['cart'] ?? []) . ')</a> ';
    if ($user !== null) {
        echo '<a href="12_order_history.php">My orders</a> <a href="17_wishlist.php">Wishlist</a> ';
        if (($user['role'] ?? '') === 'admin') {
            echo '<a href="08_admin.php">Admin</a> ';
        }
        echo '<a href="05_auth.php?action=logout">Sign out</a>';
    } else {
        echo '<a href="05_auth.php">Sign in</a>';
    }
    echo '</nav></header><main class="container">';
    echo '<h1>' . e($title) . '</h1>';
    if ($flash !== null) {
        echo '<p class="notice" role="status">' . e($flash) . '</p>';
    }
}

function bookstore_footer(): void
{
    echo '</main><footer class="site-footer">Thoughtful reads, delivered to your door.</footer></body></html>';
}

function book_card(array $book): void
{
    echo '<article class="book-card">';
    if (!empty($book['image'])) {
        echo '<img src="' . e($book['image']) . '" alt="">';
    }
    echo '<h2><a href="04_book.php?id=' . (int) $book['id'] . '">' . e($book['title']) . '</a></h2>';
    echo '<p>by ' . e($book['author']) . '</p><p class="price">$' . number_format((float) $book['price'], 2) . '</p>';
    echo '<p>' . ((int) $book['stock'] > 0 ? 'In stock: ' . (int) $book['stock'] : 'Out of stock') . '</p>';
    echo '<form method="post" action="13_stock_check.php">' . csrf_field();
    echo '<input type="hidden" name="book_id" value="' . (int) $book['id'] . '">';
    echo '<input type="hidden" name="quantity" value="1">';
    echo '<button type="submit"' . ((int) $book['stock'] < 1 ? ' disabled' : '') . '>Add to cart</button></form>';
    echo '</article>';
}
