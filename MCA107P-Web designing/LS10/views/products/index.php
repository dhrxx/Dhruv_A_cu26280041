<?php if (!$products): ?>
  <p>No products have been added yet.</p>
<?php else: ?>
  <ul id="product-list" class="products">
    <?php foreach ($products as $product): ?>
      <li><strong><?= escape($product['name']) ?></strong>
        <p><?= escape($product['description']) ?></p>
        <p>Price: <?= escape($product['price']) ?></p>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
<p>API: <code>GET /api/products</code>; authorized writes use a bearer token.</p>
<form id="api-auth-form" class="form-card">
  <h2>Load products through the API</h2>
  <label>Email <input type="email" name="email" autocomplete="username" required></label>
  <label>Password <input type="password" name="password" autocomplete="current-password" required></label>
  <button type="submit">Sign in to API</button>
  <p id="api-status" role="status"></p>
</form>
<script src="/08_api_fetch.js" defer></script>
<p><a href="/upload">Upload an image</a></p>
