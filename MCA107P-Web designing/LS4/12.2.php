<?php
// Login form and authentication. Passwords in users.password_hash must be made with password_hash().
require __DIR__ . '/db_connect.php';
session_start();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT id, username, password_hash FROM users WHERE username = :u');
    $stmt->execute(['u' => $username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['username'] = $user['username'];
        $message = 'Login successful.';
    } else {
        $message = 'Invalid username or password.';
    }
}
?>
<!doctype html><html lang="en"><meta charset="utf-8"><title>Student Login</title><body>
<h1>Login</h1><p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
<form method="post"><label>Username <input name="username" required></label><br>
<label>Password <input type="password" name="password" required></label><br><button>Login</button></form>
</body></html>
