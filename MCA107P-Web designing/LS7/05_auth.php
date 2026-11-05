<?php
// Implement a user registration and login system in PHP with password hashing and secure session management.
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php';

if (($_GET['action'] ?? '') === 'logout') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verify_csrf();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        redirect('01_home.php');
    }
    bookstore_header('Sign out');
    ?>
    <form method="post"><?= csrf_field() ?><button type="submit">Confirm sign out</button></form>
    <?php bookstore_footer(); exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim((string) ($_POST['name'] ?? ''));
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $password = (string) ($_POST['password'] ?? '');
    $action = (string) ($_POST['action'] ?? 'login');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 254 || strlen($password) < 10) {
        $error = 'Enter a valid email and a password of at least 10 characters.';
    } elseif ($action === 'register' && ($name === '' || strlen($name) > 120)) {
        $error = 'Enter your name (up to 120 characters).';
    } else {
        if ($action === 'register') {
            try {
                $insert = db()->prepare(
                    'INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :hash)'
                );
                $insert->execute(['name' => $name, 'email' => $email, 'hash' => password_hash($password, PASSWORD_DEFAULT)]);
                $userId = (int) db()->lastInsertId();
            } catch (PDOException $exception) {
                if ($exception->getCode() === '23000') {
                    $error = 'An account with that email already exists.';
                } else {
                    throw $exception;
                }
            }
        } else {
            $select = db()->prepare('SELECT id, name, email, password_hash, role FROM users WHERE email = :email');
            $select->execute(['email' => $email]);
            $account = $select->fetch();
            if ($account && password_verify($password, $account['password_hash'])) {
                if (password_needs_rehash($account['password_hash'], PASSWORD_DEFAULT)) {
                    $update = db()->prepare('UPDATE users SET password_hash = :hash WHERE id = :id');
                    $update->execute(['hash' => password_hash($password, PASSWORD_DEFAULT), 'id' => $account['id']]);
                }
                $userId = (int) $account['id'];
            } else {
                $error = 'Email or password is incorrect.';
            }
        }
        if (isset($userId)) {
            session_regenerate_id(true);
            $load = db()->prepare('SELECT id, name, email, role FROM users WHERE id = :id');
            $load->execute(['id' => $userId]);
            $_SESSION['user'] = $load->fetch();
            redirect('01_home.php');
        }
    }
}

bookstore_header('Account');
if ($error !== ''): ?><p class="error" role="alert"><?= e($error) ?></p><?php endif; ?>
<div class="form-grid">
    <form method="post" data-validate>
        <h2>Sign in</h2><?= csrf_field() ?><input type="hidden" name="action" value="login">
        <label>Email <input type="email" name="email" maxlength="254" required autocomplete="email"></label>
        <label>Password <input type="password" name="password" minlength="10" required autocomplete="current-password"></label>
        <button type="submit">Sign in</button>
    </form>
    <form method="post" data-validate>
        <h2>Create account</h2><?= csrf_field() ?><input type="hidden" name="action" value="register">
        <label>Name <input type="text" name="name" maxlength="120" required autocomplete="name"></label>
        <label>Email <input type="email" name="email" maxlength="254" required autocomplete="email"></label>
        <label>Password <input type="password" name="password" minlength="10" required autocomplete="new-password"></label>
        <button type="submit">Register</button>
    </form>
</div>
<?php bookstore_footer(); ?>
