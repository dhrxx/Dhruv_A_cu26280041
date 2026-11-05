<?php
// Implement an edit form that pre-fills existing record data and updates it in the database using an UPDATE query.
declare(strict_types=1);
require_once __DIR__ . '/02_database.php';
require_once __DIR__ . '/14_product_upload.php';
require_admin();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(400);
    exit('A valid product ID is required.');
}
$select = db()->prepare('SELECT id, name, category, description, price, stock FROM products WHERE id = :id');
$select->execute(['id' => $id]);
$product = $select->fetch();
if (!$product) {
    http_response_code(404);
    exit('Product not found.');
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    try {
        $name = trim((string) ($_POST['name'] ?? ''));
        $category = trim((string) ($_POST['category'] ?? ''));
        $description = trim((string) ($_POST['description'] ?? ''));
        $price = filter_var($_POST['price'] ?? null, FILTER_VALIDATE_FLOAT);
        $stock = filter_var($_POST['stock'] ?? null, FILTER_VALIDATE_INT);
        if ($name === '' || strlen($name) > 180 || $category === '' || strlen($category) > 100
            || $price === false || $price < 0 || $stock === false || $stock < 0) {
            throw new InvalidArgumentException('Enter a name, category, non-negative price, and whole-number stock.');
        }
        $image = product_image_upload();
        if ($image === null) {
            $update = db()->prepare(
                'UPDATE products SET name = :name, category = :category, description = :description,
                 price = :price, stock = :stock WHERE id = :id'
            );
            $values = ['name' => $name, 'category' => $category, 'description' => $description,
                'price' => number_format((float) $price, 2, '.', ''), 'stock' => $stock, 'id' => $id];
        } else {
            $update = db()->prepare(
                'UPDATE products SET name = :name, category = :category, description = :description,
                 price = :price, stock = :stock, image_path = :image WHERE id = :id'
            );
            $values = ['name' => $name, 'category' => $category, 'description' => $description,
                'price' => number_format((float) $price, 2, '.', ''), 'stock' => $stock, 'image' => $image, 'id' => $id];
        }
        $update->execute($values);
        $_SESSION['flash'] = 'Product updated.';
        header('Location: 08_products.php', true, 303);
        exit;
    } catch (InvalidArgumentException | ProductUploadException $exception) {
        $error = $exception->getMessage();
    }
}
page_header('Edit product');
if ($error !== '') echo '<p class="error" role="alert">' . e($error) . '</p>';
?>
<form method="post" enctype="multipart/form-data" class="form-card">
  <?= csrf_field() ?><input type="hidden" name="MAX_FILE_SIZE" value="2097152">
  <label>Name <input name="name" maxlength="180" value="<?= e($product['name']) ?>" required></label>
  <label>Category <input name="category" maxlength="100" value="<?= e($product['category']) ?>" required></label>
  <label>Description <textarea name="description" rows="4"><?= e($product['description']) ?></textarea></label>
  <label>Price <input type="number" name="price" min="0" step="0.01" value="<?= e($product['price']) ?>" required></label>
  <label>Stock <input type="number" name="stock" min="0" value="<?= (int) $product['stock'] ?>" required></label>
  <label>Replace image (optional; JPEG, PNG, WebP; up to 2 MB) <input type="file" name="photo" accept="image/jpeg,image/png,image/webp"></label>
  <button type="submit">Save changes</button>
</form>
<?php page_footer(); ?>
