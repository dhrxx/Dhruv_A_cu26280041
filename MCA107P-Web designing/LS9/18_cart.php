<?php
// Implement a shopping cart using PHP sessions that supports adding, updating quantities, and removing items.
declare(strict_types=1);
require_once __DIR__ . '/02_database.php';
$_SESSION['cart'] ??= [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = (string) ($_POST['action'] ?? '');
    $productId = filter_var($_POST['product_id'] ?? null, FILTER_VALIDATE_INT);
    if ($action === 'clear') {
        $_SESSION['cart'] = [];
        $_SESSION['flash'] = 'Cart emptied.';
    } elseif ($productId && in_array($action, ['add', 'update', 'remove'], true)) {
        if ($action === 'remove') {
            unset($_SESSION['cart'][$productId]);
            $_SESSION['flash'] = 'Item removed from cart.';
        } else {
            $quantity = filter_var($_POST['quantity'] ?? null, FILTER_VALIDATE_INT);
            $select = db()->prepare('SELECT stock FROM products WHERE id = :id');
            $select->execute(['id' => $productId]);
            $stock = $select->fetchColumn();
            $newQuantity = $action === 'add'
                ? (int) ($_SESSION['cart'][$productId] ?? 0) + (int) $quantity
                : (int) $quantity;
            if ($stock === false || !$quantity || $quantity < 1 || $newQuantity > (int) $stock) {
                $_SESSION['flash'] = 'Quantity exceeds available product stock.';
            } else {
                $_SESSION['cart'][$productId] = $newQuantity;
                $_SESSION['flash'] = 'Cart updated.';
            }
        }
    } else {
        $_SESSION['flash'] = 'Invalid cart action.';
    }
    header('Location: 18_cart.php', true, 303);
    exit;
}

$cart = $_SESSION['cart'];
$items = [];
if ($cart !== []) {
    $ids = array_values(array_filter(array_map('intval', array_keys($cart))));
    if ($ids !== []) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $statement = db()->prepare(
            "SELECT id, name, price, stock FROM products WHERE id IN ($placeholders) ORDER BY name"
        );
        $statement->execute($ids);
        $items = $statement->fetchAll();
    }
}
page_header('Your cart');
$totalCents = 0;
?>
<?php if ($items === []): ?>
  <p>Your cart is empty. <a href="08_products.php">Browse products</a>.</p>
<?php else: ?>
  <div class="table-wrap"><table><thead><tr><th>Product</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th></th></tr></thead><tbody>
  <?php foreach ($items as $item):
      $quantity = (int) ($cart[$item['id']] ?? 0);
      $subtotalCents = (int) round((float) $item['price'] * 100) * $quantity;
      $totalCents += $subtotalCents;
  ?>
    <tr><td><?= e($item['name']) ?></td><td>$<?= number_format((float) $item['price'], 2) ?></td>
      <td><form method="post" class="inline-form"><?= csrf_field() ?><input type="hidden" name="product_id" value="<?= (int) $item['id'] ?>"><input type="hidden" name="action" value="update">
        <label class="sr-only" for="quantity-<?= (int) $item['id'] ?>">Quantity</label><input id="quantity-<?= (int) $item['id'] ?>" type="number" name="quantity" min="1" max="<?= (int) $item['stock'] ?>" value="<?= $quantity ?>" required><button>Update</button>
      </form></td><td>$<?= number_format($subtotalCents / 100, 2) ?></td>
      <td><form method="post"><?= csrf_field() ?><input type="hidden" name="product_id" value="<?= (int) $item['id'] ?>"><input type="hidden" name="action" value="remove"><button class="button-danger">Remove</button></form></td>
    </tr>
  <?php endforeach; ?>
  </tbody></table></div>
  <p class="total">Total: $<?= number_format($totalCents / 100, 2) ?></p>
  <p><a class="button" href="19_checkout.php">Proceed to checkout</a></p>
  <form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="clear"><button class="button-secondary">Empty cart</button></form>
<?php endif; ?>
<?php page_footer(); ?>
