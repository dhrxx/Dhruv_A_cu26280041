<?php
// Create a PHP database connection file using mysqli (or PDO) with proper error handling, and include it across multiple pages using require_once.
declare(strict_types=1);

$secureCookie = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
ini_set('session.use_strict_mode', '1');
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'httponly' => true,
        'secure' => $secureCookie,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function db(): PDO
{
    static $connection;
    if ($connection instanceof PDO) {
        return $connection;
    }

    $dsn = getenv('WEBAPP_DSN') ?: 'mysql:host=127.0.0.1;dbname=webapp_db;charset=utf8mb4';
    $username = getenv('WEBAPP_DB_USER');
    if ($username === false || $username === '') {
        throw new RuntimeException('Set WEBAPP_DB_USER before connecting to the application database.');
    }
    $password = getenv('WEBAPP_DB_PASSWORD');
    try {
        $connection = new PDO($dsn, $username, $password === false ? '' : $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (PDOException $exception) {
        error_log('Application database connection failed: ' . $exception->getMessage());
        throw new RuntimeException('Could not connect to the application database. Check its configuration and server logs.', 0, $exception);
    }
    return $connection;
}

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
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
    $value = $_POST['csrf_token'] ?? '';
    if (!is_string($value) || !hash_equals(csrf_token(), $value)) {
        http_response_code(400);
        exit('Invalid form token. Reload the page and try again.');
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
        header('Location: 04_login.php', true, 303);
        exit;
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

function page_header(string $title): void
{
    $user = current_user();
    echo '<!doctype html><html lang="en"><head><meta charset="utf-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>' . e($title) . ' | Web App Lab</title><link rel="stylesheet" href="21_app.css"></head><body>';
    echo '<a class="skip-link" href="#main">Skip to content</a>';
    echo '<header><a class="brand" href="08_products.php">Web App Lab</a><nav aria-label="Main navigation">';
    echo '<a href="08_products.php">Products</a> <a href="18_cart.php">Cart (' . (int) array_sum($_SESSION['cart'] ?? []) . ')</a> ';
    if ($user !== null) {
        echo '<a href="05_dashboard.php">Dashboard</a> <a href="06_logout.php">Logout</a>';
        if (($user['role'] ?? '') === 'admin') echo ' <a href="07_add_product.php">Add product</a>';
    } else {
        echo '<a href="04_login.php">Login</a> <a href="03_register.php">Register</a>';
    }
    echo '</nav></header><main id="main" class="container"><h1>' . e($title) . '</h1>';
    if (isset($_SESSION['flash'])) {
        echo '<p class="notice" role="status">' . e($_SESSION['flash']) . '</p>';
        unset($_SESSION['flash']);
    }
}

function page_footer(): void
{
    echo '</main><footer><p>PHP and MySQL coursework sample.</p></footer></body></html>';
}

function remember_user(PDO $pdo): void
{
    $cookie = $_COOKIE['webapp_remember'] ?? '';
    if (!is_string($cookie) || !preg_match('/^([a-f0-9]{24}):([a-f0-9]{64})$/', $cookie, $parts)) {
        return;
    }
    $selector = $parts[1];
    $statement = $pdo->prepare(
        'SELECT remember_tokens.token_hash, users.id, users.name, users.email, users.role
         FROM remember_tokens JOIN users ON users.id = remember_tokens.user_id
         WHERE remember_tokens.selector = :selector AND remember_tokens.expires_at > UTC_TIMESTAMP()'
    );
    $statement->execute(['selector' => $selector]);
    $row = $statement->fetch();
    if (!$row || !hash_equals($row['token_hash'], hash('sha256', $parts[2]))) {
        $delete = $pdo->prepare('DELETE FROM remember_tokens WHERE selector = :selector');
        $delete->execute(['selector' => $selector]);
        clear_remember_cookie();
        return;
    }

    session_regenerate_id(true);
    $_SESSION['user'] = [
        'id' => (int) $row['id'], 'name' => $row['name'],
        'email' => $row['email'], 'role' => $row['role'],
    ];
    $newToken = bin2hex(random_bytes(32));
    $update = $pdo->prepare(
        'UPDATE remember_tokens SET token_hash = :hash, expires_at = :expires WHERE selector = :selector'
    );
    $update->execute([
        'hash' => hash('sha256', $newToken),
        'expires' => gmdate('Y-m-d H:i:s', time() + 60 * 60 * 24 * 30),
        'selector' => $selector,
    ]);
    setcookie('webapp_remember', $selector . ':' . $newToken, [
        'expires' => time() + 60 * 60 * 24 * 30, 'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true, 'samesite' => 'Lax',
    ]);
}

function issue_remember_cookie(PDO $pdo, int $userId): void
{
    $selector = bin2hex(random_bytes(12));
    $token = bin2hex(random_bytes(32));
    $statement = $pdo->prepare(
        'INSERT INTO remember_tokens (selector, user_id, token_hash, expires_at)
         VALUES (:selector, :user_id, :hash, :expires)'
    );
    $statement->execute([
        'selector' => $selector, 'user_id' => $userId,
        'hash' => hash('sha256', $token),
        'expires' => gmdate('Y-m-d H:i:s', time() + 60 * 60 * 24 * 30),
    ]);
    setcookie('webapp_remember', $selector . ':' . $token, [
        'expires' => time() + 60 * 60 * 24 * 30, 'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true, 'samesite' => 'Lax',
    ]);
}

function clear_remember_cookie(): void
{
    setcookie('webapp_remember', '', [
        'expires' => time() - 3600, 'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true, 'samesite' => 'Lax',
    ]);
}
