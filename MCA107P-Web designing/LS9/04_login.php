<?php
// Implement a login page that verifies credentials using password_verify() and starts a user session with session_start().
declare(strict_types=1);
require_once __DIR__ . '/02_database.php';
$pdo = db();
if (current_user() === null) remember_user($pdo);
if (current_user() !== null) {
    header('Location: 05_dashboard.php', true, 303);
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $password = (string) ($_POST['password'] ?? '');
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($email) <= 254) {
        $statement = $pdo->prepare(
            'SELECT id, name, email, password_hash, role FROM users WHERE email = :email'
        );
        $statement->execute(['email' => $email]);
        $user = $statement->fetch();
    } else {
        $user = false;
    }
    if ($user && password_verify($password, $user['password_hash'])) {
        if (password_needs_rehash($user['password_hash'], PASSWORD_DEFAULT)) {
            $update = $pdo->prepare('UPDATE users SET password_hash = :hash WHERE id = :id');
            $update->execute(['hash' => password_hash($password, PASSWORD_DEFAULT), 'id' => $user['id']]);
        }
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int) $user['id'], 'name' => $user['name'],
            'email' => $user['email'], 'role' => $user['role'],
        ];
        if (isset($_POST['remember'])) issue_remember_cookie($pdo, (int) $user['id']);
        header('Location: 05_dashboard.php', true, 303);
        exit;
    }
    $error = 'Email or password is incorrect.';
}
page_header('Sign in');
if ($error !== '') echo '<p class="error" role="alert">' . e($error) . '</p>';
?>
<form method="post" class="form-card">
  <?= csrf_field() ?>
  <label>Email <input type="email" name="email" maxlength="254" autocomplete="email" required></label>
  <label>Password <input type="password" name="password" autocomplete="current-password" required></label>
  <label class="checkbox-label"><input type="checkbox" name="remember" value="1"> Remember me on this device for 30 days</label>
  <button type="submit">Sign in</button>
</form>
<?php page_footer(); ?>
