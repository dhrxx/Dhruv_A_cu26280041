<?php
// Create a shopping cart system using PHP sessions (`$_SESSION['cart']`) allowing users to add books, update quantities, and remove items.
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php';
$_SESSION['cart'] ??= [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $id = filter_var($_POST['book_id'] ?? null, FILTER_VALIDATE_INT);
    $action = (string) ($_POST['action'] ?? '');
    if ($id && $action === 'remove') {
        unset($_SESSION['cart'][$id]);
        set_flash('Book removed from the cart.');
    } elseif ($id && $action === 'update') {
        $quantity = filter_var($_POST['quantity'] ?? null, FILTER_VALIDATE_INT);
        $stock = db()->prepare('SELECT stock FROM books WHERE id = :id');
        $stock->execute(['id' => $id]);
        $available = $stock->fetchColumn();
        if ($available === false || $quantity < 1 || $quantity > (int) $available) {
            set_flash('Quantity is outside the available stock.');
        } else {
            $_SESSION['cart'][$id] = $quantity;
            set_flash('Cart quantity updated.');
        }
    }
    redirect('06_cart.php');
}

$cart = $_SESSION['cart'];
$items = [];
if ($cart !== []) {
    $ids = array_values(array_filter(array_map('intval', array_keys($cart))));
    if ($ids !== []) {
        $marks = implode(',', array_fill(0, count($ids), '?'));
        $query = db()->prepare("SELECT id, title, author, price, stock, image FROM books WHERE id IN ($marks)");
        $query->execute($ids);
        $items = $query->fetchAll();
    }
}
bookstore_header('Your cart');
$total = 0.0;
?>
<?php if ($items === []): ?>
    <p>Your cart is empty. <a href="03_books.php">Browse books</a>.</p>
<?php else: ?>
    <div class="table-wrap"><table><thead><tr><th>Book</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th></th></tr></thead><tbody>
    <?php foreach ($items as $item):
        $quantity = (int) ($cart[$item['id']] ?? 0);
        $subtotal = (float) $item['price'] * $quantity;
        $total += $subtotal;
    ?>
        <tr><td><?= e($item['title']) ?></td><td>$<?= number_format((float) $item['price'], 2) ?></td>
        <td><form method="post" class="inline-form"><?= csrf_field() ?><input type="hidden" name="book_id" value="<?= (int) $item['id'] ?>"><input type="hidden" name="action" value="update">
        <input type="number" name="quantity" min="1" max="<?= (int) $item['stock'] ?>" value="<?= $quantity ?>" required><button>Update</button></form></td>
        <td>$<?= number_format($subtotal, 2) ?></td><td><form method="post"><?= csrf_field() ?><input type="hidden" name="book_id" value="<?= (int) $item['id'] ?>"><input type="hidden" name="action" value="remove"><button class="button-secondary">Remove</button></form></td></tr>
    <?php endforeach; ?>
    </tbody></table></div>
    <p class="price">Total: $<?= number_format($total, 2) ?></p>
    <p><a class="button-link" href="07_checkout.php">Proceed to checkout</a></p>
<?php endif; ?>
<?php bookstore_footer(); ?>
