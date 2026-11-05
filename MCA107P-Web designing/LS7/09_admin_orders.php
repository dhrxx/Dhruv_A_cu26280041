<?php
// Write a PHP script enabling administrators to view all customer orders and update order fulfillment status (Pending, Shipped, Delivered).
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php';
require_admin();
$statuses = ['Pending', 'Shipped', 'Delivered'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $id = filter_var($_POST['order_id'] ?? null, FILTER_VALIDATE_INT);
    $status = (string) ($_POST['status'] ?? '');
    if (!$id || !in_array($status, $statuses, true)) {
        http_response_code(400);
        exit('Choose a valid order and fulfillment status.');
    }
    $update = db()->prepare('UPDATE orders SET status = :status WHERE id = :id');
    $update->execute(['status' => $status, 'id' => $id]);
    set_flash('Order status updated.');
    redirect('09_admin_orders.php');
}

$orders = db()->query(
    'SELECT orders.id, orders.created_at, orders.status, orders.total,
            users.name, users.email
     FROM orders JOIN users ON users.id = orders.user_id
     ORDER BY orders.created_at DESC'
)->fetchAll();
bookstore_header('Customer orders');
?>
<div class="table-wrap"><table><thead><tr><th>Order</th><th>Customer</th><th>Date</th><th>Total</th><th>Status</th><th>Update</th></tr></thead><tbody>
<?php foreach ($orders as $order): ?><tr>
    <td><a href="16_invoice.php?id=<?= (int) $order['id'] ?>">#<?= (int) $order['id'] ?></a></td>
    <td><?= e($order['name']) ?><br><?= e($order['email']) ?></td><td><?= e($order['created_at']) ?></td>
    <td>$<?= number_format((float) $order['total'], 2) ?></td><td><?= e($order['status']) ?></td>
    <td><form method="post" class="inline-form"><?= csrf_field() ?><input type="hidden" name="order_id" value="<?= (int) $order['id'] ?>">
        <label class="sr-only" for="status-<?= (int) $order['id'] ?>">Order status</label><select id="status-<?= (int) $order['id'] ?>" name="status">
            <?php foreach ($statuses as $status): ?><option<?= $status === $order['status'] ? ' selected' : '' ?>><?= e($status) ?></option><?php endforeach; ?>
        </select><button>Save</button></form></td>
</tr><?php endforeach; ?>
</tbody></table></div>
<?php if ($orders === []): ?><p>There are no orders yet.</p><?php endif; ?>
<?php bookstore_footer(); ?>
