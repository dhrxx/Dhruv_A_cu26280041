<?php
// Develop a checkout page in PHP where users enter shipping details and place an order, recording transaction data into the MySQL database.
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php';
$user = require_login();
$cart = $_SESSION['cart'] ?? [];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $shipping = [
        'name' => trim((string) ($_POST['shipping_name'] ?? '')),
        'address' => trim((string) ($_POST['shipping_address'] ?? '')),
        'city' => trim((string) ($_POST['shipping_city'] ?? '')),
        'postal_code' => trim((string) ($_POST['shipping_postal_code'] ?? '')),
    ];
    if ($shipping['name'] === '' || strlen($shipping['name']) > 120
        || $shipping['address'] === '' || strlen($shipping['address']) > 255
        || $shipping['city'] === '' || strlen($shipping['city']) > 120
        || $shipping['postal_code'] === '' || strlen($shipping['postal_code']) > 24) {
        $error = 'Complete all shipping fields using the allowed length.';
    }
    if ($cart === []) {
        $error = 'Your cart is empty.';
    }
    if ($error === '') {
        try {
            $orderId = place_order(db(), (int) $user['id'], $cart, $shipping);
            $_SESSION['cart'] = [];
            redirect('16_invoice.php?id=' . $orderId);
        } catch (DomainException $exception) {
            $error = $exception->getMessage();
        }
    }
}

bookstore_header('Checkout');
if ($error !== ''): ?><p class="error" role="alert"><?= e($error) ?></p><?php endif; ?>
<form method="post" data-validate><?= csrf_field() ?>
    <label>Recipient name <input name="shipping_name" maxlength="120" required autocomplete="name" value="<?= e($user['name']) ?>"></label>
    <label>Address <input name="shipping_address" maxlength="255" required autocomplete="street-address"></label>
    <label>City <input name="shipping_city" maxlength="120" required autocomplete="address-level2"></label>
    <label>Postal code <input name="shipping_postal_code" maxlength="24" required autocomplete="postal-code"></label>
    <button type="submit"<?= $cart === [] ? ' disabled' : '' ?>>Place order</button>
</form>
<?php bookstore_footer(); ?>
