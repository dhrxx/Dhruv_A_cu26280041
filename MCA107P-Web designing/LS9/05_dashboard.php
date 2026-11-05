<?php
// Create a protected dashboard page that redirects unauthenticated visitors to the login page using session checks.
declare(strict_types=1);
require_once __DIR__ . '/02_database.php';
if (current_user() === null) {
    remember_user(db());
}
$user = require_login();
page_header('Your dashboard');
?>
<section class="panel">
  <h2>Welcome, <?= e($user['name']) ?>!</h2>
  <p>You are signed in as <?= e($user['email']) ?>.</p>
  <p><a class="button" href="08_products.php">Browse products</a></p>
  <?php if (($user['role'] ?? '') === 'admin'): ?>
    <p><a href="07_add_product.php">Add a product</a> · <a href="08_products.php">Manage catalogue</a></p>
  <?php endif; ?>
</section>
<?php page_footer(); ?>
