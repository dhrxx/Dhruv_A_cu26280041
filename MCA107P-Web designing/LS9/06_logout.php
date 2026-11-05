<?php
// Implement a secure logout script that destroys the session, clears session variables, and removes the session cookie.
declare(strict_types=1);
require_once __DIR__ . '/02_database.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    page_header('Sign out');
    ?>
    <form method="post" class="form-card">
      <?= csrf_field() ?>
      <p>Sign out of this account on this device?</p>
      <button type="submit">Sign out</button>
    </form>
    <?php
    page_footer();
    exit;
}
verify_csrf();
$remember = $_COOKIE['webapp_remember'] ?? '';
if (is_string($remember) && preg_match('/^([a-f0-9]{24}):[a-f0-9]{64}$/', $remember, $matches)) {
    $delete = db()->prepare('DELETE FROM remember_tokens WHERE selector = :selector');
    $delete->execute(['selector' => $matches[1]]);
}
clear_remember_cookie();
$_SESSION = [];
$params = session_get_cookie_params();
if (ini_get('session.use_cookies')) {
    setcookie(session_name(), '', [
        'expires' => time() - 42000, 'path' => $params['path'],
        'domain' => $params['domain'], 'secure' => $params['secure'],
        'httponly' => $params['httponly'], 'samesite' => 'Lax',
    ]);
}
session_destroy();
header('Location: 04_login.php', true, 303);
exit;
