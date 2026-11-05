<?php
// Build a user registration page with server-side validation and store passwords securely using password_hash().
declare(strict_types=1);
require_once __DIR__ . '/02_database.php';
if (current_user() !== null) {
    header('Location: 05_dashboard.php', true, 303);
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim((string) ($_POST['name'] ?? ''));
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $password = (string) ($_POST['password'] ?? '');
    if ($name === '' || strlen($name) > 120 || !filter_var($email, FILTER_VALIDATE_EMAIL)
        || strlen($email) > 254 || strlen($password) < 10 || strlen($password) > 4096) {
        $error = 'Enter a name, a valid email address, and a password of at least 10 characters.';
    } else {
        try {
            $statement = db()->prepare(
                'INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password_hash)'
            );
            $statement->execute([
                'name' => $name, 'email' => $email,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            ]);
            $_SESSION['flash'] = 'Account created. You can now sign in.';
            header('Location: 04_login.php', true, 303);
            exit;
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') {
                $error = 'An account with that email already exists.';
            } else {
                throw $exception;
            }
        }
    }
}
page_header('Create your account');
if ($error !== '') echo '<p class="error" role="alert">' . e($error) . '</p>';
?>
<form method="post" class="form-card">
  <?= csrf_field() ?>
  <label>Name <input name="name" maxlength="120" autocomplete="name" required></label>
  <label>Email <input type="email" name="email" maxlength="254" autocomplete="email" required></label>
  <label>Password <input type="password" name="password" minlength="10" maxlength="4096" autocomplete="new-password" required></label>
  <button type="submit">Register</button>
</form>
<?php page_footer(); ?>
