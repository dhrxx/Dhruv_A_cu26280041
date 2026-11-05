<?php
// Create a checkout script that saves the cart contents into the orders table using a database transaction (BEGIN, COMMIT, ROLLBACK).
declare(strict_types=1);
require_once __DIR__ . '/02_database.php';
require_once __DIR__ . '/order_service.php';
$user = require_login();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $cart = $_SESSION['cart'] ?? [];
    if ($cart === []) {
        $error = 'Your cart is empty.';
    } else {
        $pdo = db();
        try {
            $orderId = place_order($pdo, (int) $user['id'], $cart);
            $_SESSION['cart'] = [];
            $_SESSION['flash'] = 'Order #' . $orderId . ' placed successfully.';
            header('Location: 05_dashboard.php', true, 303);
            exit;
        } catch (DomainException $exception) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $error = $exception->getMessage();
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            throw $exception;
        }
    }
}
page_header('Checkout');
if ($error !== '') echo '<p class="error" role="alert">' . e($error) . '</p>';
?>
<p>Signed in as <?= e($user['email']) ?>. We will verify current stock and price before placing your order.</p>
<form method="post" class="form-card"><?= csrf_field() ?>
  <p>Order total: calculated securely from the database at checkout.</p>
  <button type="submit"<?= empty($_SESSION['cart']) ? ' disabled' : '' ?>>Place order</button>
</form>
<?php page_footer(); ?>
