<?php
// Write a PHP script to generate an invoice summary page after successful order placement.
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php';
$user = require_login();
$orderId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$statement = db()->prepare(
    'SELECT id, user_id, shipping_name, shipping_address, shipping_city,
            shipping_postal_code, status, total, created_at
     FROM orders WHERE id = :id'
);
$statement->execute(['id' => $orderId ?: 0]);
$order = $statement->fetch();
if (!$order || ((int) $order['user_id'] !== (int) $user['id'] && ($user['role'] ?? '') !== 'admin')) {
    http_response_code(404);
    $order = null;
}
bookstore_header('Order invoice');
if ($order):
    $items = db()->prepare('SELECT title_at_purchase, quantity, unit_price FROM order_items WHERE order_id = :id');
    $items->execute(['id' => $order['id']]);
?>
<section class="invoice">
    <p><strong>Order #<?= (int) $order['id'] ?></strong> · <?= e($order['created_at']) ?></p>
    <p>Fulfillment: <?= e($order['status']) ?></p>
    <address><?= e($order['shipping_name']) ?><br><?= e($order['shipping_address']) ?><br>
        <?= e($order['shipping_city']) ?>, <?= e($order['shipping_postal_code']) ?></address>
    <div class="table-wrap"><table><thead><tr><th>Book</th><th>Qty</th><th>Unit price</th><th>Line total</th></tr></thead><tbody>
        <?php foreach ($items->fetchAll() as $item): ?><tr>
            <td><?= e($item['title_at_purchase']) ?></td><td><?= (int) $item['quantity'] ?></td>
            <td>$<?= number_format((float) $item['unit_price'], 2) ?></td>
            <td>$<?= number_format((float) $item['unit_price'] * (int) $item['quantity'], 2) ?></td>
        </tr><?php endforeach; ?>
    </tbody></table></div>
    <p class="price">Total paid: $<?= number_format((float) $order['total'], 2) ?></p>
</section>
<?php else: ?>
<p>That order could not be found.</p>
<?php endif; ?>
<?php bookstore_footer(); ?>
