<?php
// Display all product records in a styled HTML table by fetching data from MySQL with a SELECT query.
declare(strict_types=1);
require_once __DIR__ . '/02_database.php';
require_once __DIR__ . '/11_product_pagination.php';
require_once __DIR__ . '/12_product_search.php';
require_once __DIR__ . '/13_product_sort.php';

$pageSize = 10;
$filters = product_search_filters($_GET);
$sort = product_sort($_GET['sort'] ?? 'name', $_GET['direction'] ?? 'asc');
$total = count_products(db(), $filters);
$page = max(1, (int) filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT));
$pageCount = max(1, (int) ceil($total / $pageSize));
$page = min($page, $pageCount);
$products = find_products(db(), $filters, $sort, $pageSize, ($page - 1) * $pageSize);

page_header('Product catalogue');
if (current_user() !== null && (current_user()['role'] ?? '') === 'admin') {
    echo '<p><a class="button" href="07_add_product.php">Add product</a></p>';
}
?>
<form class="filter-form" method="get" action="08_products.php" data-live-search>
  <label>Product name <input name="name" value="<?= e($filters['name']) ?>" maxlength="100" autocomplete="off" data-search-input></label>
  <label>Category <input name="category" value="<?= e($filters['category']) ?>" maxlength="100"></label>
  <label>Minimum price <input name="min_price" type="number" min="0" step="0.01" value="<?= e($filters['min_price']) ?>"></label>
  <label>Maximum price <input name="max_price" type="number" min="0" step="0.01" value="<?= e($filters['max_price']) ?>"></label>
  <button type="submit">Filter</button>
  <ul class="suggestions" data-suggestions hidden></ul>
</form>
<p data-live-status aria-live="polite"></p>
<p><?= $total ?> product<?= $total === 1 ? '' : 's' ?> found.</p>
<div class="table-wrap">
<table>
  <thead><tr>
    <?php foreach (['name' => 'Product', 'category' => 'Category', 'price' => 'Price', 'stock' => 'Stock'] as $key => $label): ?>
      <th scope="col"><a href="<?= e(sort_url($key, $sort, $_GET)) ?>"><?= e($label) ?><?= $sort['column'] === $key ? ($sort['direction'] === 'ASC' ? ' ▲' : ' ▼') : '' ?></a></th>
    <?php endforeach; ?>
    <th scope="col">Actions</th>
  </tr></thead>
  <tbody>
  <?php foreach ($products as $product): ?>
    <tr>
      <td><?php if ($product['image_path'] !== null): ?><img class="product-thumb" src="<?= e($product['image_path']) ?>" alt="Photo of <?= e($product['name']) ?>"><?php endif; ?><?= e($product['name']) ?></td>
      <td><?= e($product['category']) ?></td><td>$<?= number_format((float) $product['price'], 2) ?></td><td><?= (int) $product['stock'] ?></td>
      <td>
      <?php if (current_user() !== null && (current_user()['role'] ?? '') === 'admin'): ?>
        <a href="09_edit_product.php?id=<?= (int) $product['id'] ?>">Edit</a>
        <form class="inline-form delete-form" method="post" action="10_delete_product.php">
          <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
          <button class="button-danger" type="submit">Delete</button>
        </form>
      <?php else: ?>
        <form class="inline-form" method="post" action="18_cart.php">
          <?= csrf_field() ?><input type="hidden" name="action" value="add"><input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
          <label class="sr-only" for="qty-<?= (int) $product['id'] ?>">Quantity</label><input id="qty-<?= (int) $product['id'] ?>" type="number" name="quantity" value="1" min="1" max="<?= (int) $product['stock'] ?>" required>
          <button type="submit"<?= (int) $product['stock'] < 1 ? ' disabled' : '' ?>>Add to cart</button>
        </form>
      <?php endif; ?>
      </td>
    </tr>
  <?php endforeach; ?>
  <?php if ($products === []): ?><tr><td colspan="5">No products match these filters.</td></tr><?php endif; ?>
  </tbody>
</table>
</div>
<?= pagination_links($page, $pageCount, $_GET) ?>
<script src="20_live_search.js" defer></script>
<script src="delete_confirmation.js" defer></script>
<?php page_footer(); ?>
