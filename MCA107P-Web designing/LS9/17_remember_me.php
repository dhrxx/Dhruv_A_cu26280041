<?php
// Use cookies to implement a "Remember Me" option on the login form and display a personalized welcome message.
declare(strict_types=1);
require_once __DIR__ . '/02_database.php';
$user = current_user();
if ($user === null) {
    remember_user(db());
    $user = current_user();
}
if ($user === null) {
    header('Location: 04_login.php', true, 303);
    exit;
}
page_header('Welcome back');
?>
<section class="panel"><h2>Welcome, <?= e($user['name']) ?>!</h2>
<p>You are signed in as <?= e($user['email']) ?>.</p>
<p>If you chose “Remember me,” this device can restore your session for up to 30 days. The browser cookie contains a random token; only its hash is stored in the database.</p></section>
<?php page_footer(); ?>
