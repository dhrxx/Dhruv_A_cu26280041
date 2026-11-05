<?php
// Create a customer order history page where logged-in users can view their past purchases and status.
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php';
$user = require_login();
$statement = db()->prepare(
    'SELECT id, created_at, status, total FROM orders WHERE user_id = :user_id ORDER BY created_at DESC'
);
$statement->execute(['user_id' => $user['id']]);
$orders = $statement->fetchAll();
bookstore_header('Your order history');
?>
<div class="table-wrap"><table><thead><tr><th>Order</th><th>Date</th><th>Status</th><th>Total</th></tr></thead><tbody>
<?php foreach ($orders as $order): ?><tr><td><a href="16_invoice.php?id=<?= (int) $order['id'] ?>">#<?= (int) $order['id'] ?></a></td>
    <td><?= e($order['created_at']) ?></td><td><?= e($order['status']) ?></td>
    <td>$<?= number_format((float) $order['total'], 2) ?></td></tr><?php endforeach; ?>
</tbody></table></div>
<?php if ($orders === []): ?><p>You have not placed any orders yet.</p><?php endif; ?>
<?php bookstore_footer(); ?>
