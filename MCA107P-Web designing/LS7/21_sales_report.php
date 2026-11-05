<?php
// Write a PHP script for administrators to view sales summary reports and total revenue generated.
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php';
require_admin();
$summary = db()->query(
    "SELECT COUNT(*) AS order_count,
            COALESCE(SUM(total), 0.00) AS revenue,
            COALESCE(AVG(total), 0.00) AS average_order
     FROM orders WHERE status IN ('Shipped', 'Delivered')"
)->fetch();
$byStatus = db()->query(
    'SELECT status, COUNT(*) AS order_count, COALESCE(SUM(total), 0.00) AS revenue
     FROM orders GROUP BY status ORDER BY status'
)->fetchAll();
$topBooks = db()->query(
    'SELECT order_items.title_at_purchase AS title,
            SUM(order_items.quantity) AS copies_sold,
            SUM(order_items.quantity * order_items.unit_price) AS revenue
     FROM order_items JOIN orders ON orders.id = order_items.order_id
     WHERE orders.status IN (\'Shipped\', \'Delivered\')
     GROUP BY order_items.book_id, order_items.title_at_purchase
     ORDER BY copies_sold DESC LIMIT 10'
)->fetchAll();
bookstore_header('Sales summary');
?>
<section class="summary-cards">
    <article class="book-card"><h2>Completed orders</h2><p><?= (int) $summary['order_count'] ?></p></article>
    <article class="book-card"><h2>Total revenue</h2><p class="price">$<?= number_format((float) $summary['revenue'], 2) ?></p></article>
    <article class="book-card"><h2>Average order</h2><p>$<?= number_format((float) $summary['average_order'], 2) ?></p></article>
</section>
<h2>Orders by fulfillment status</h2>
<div class="table-wrap"><table><thead><tr><th>Status</th><th>Orders</th><th>Revenue</th></tr></thead><tbody>
<?php foreach ($byStatus as $row): ?><tr><td><?= e($row['status']) ?></td><td><?= (int) $row['order_count'] ?></td><td>$<?= number_format((float) $row['revenue'], 2) ?></td></tr><?php endforeach; ?>
</tbody></table></div>
<h2>Top-selling books</h2>
<div class="table-wrap"><table><thead><tr><th>Book</th><th>Copies sold</th><th>Revenue</th></tr></thead><tbody>
<?php foreach ($topBooks as $book): ?><tr><td><?= e($book['title']) ?></td><td><?= (int) $book['copies_sold'] ?></td><td>$<?= number_format((float) $book['revenue'], 2) ?></td></tr><?php endforeach; ?>
</tbody></table></div>
<p>Revenue counts orders marked Shipped or Delivered; pending orders are excluded.</p>
<?php bookstore_footer(); ?>
