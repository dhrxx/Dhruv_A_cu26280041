<?php
// Write PHP code to insert new product records into the database using prepared statements with bound parameters.
declare(strict_types=1);
require_once __DIR__ . '/02_database.php';
require_once __DIR__ . '/14_product_upload.php';
require_admin();
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
        $statement = db()->prepare(
            'INSERT INTO products (name, category, description, price, stock, image_path)
             VALUES (:name, :category, :description, :price, :stock, :image_path)'
        );
        $statement->execute([
            'name' => $name, 'category' => $category, 'description' => $description,
            'price' => number_format((float) $price, 2, '.', ''),
            'stock' => $stock, 'image_path' => $image,
        ]);
        $_SESSION['flash'] = 'Product added.';
        header('Location: 08_products.php', true, 303);
        exit;
    } catch (InvalidArgumentException | ProductUploadException $exception) {
        $error = $exception->getMessage();
    }
}
page_header('Add a product');
if ($error !== '') echo '<p class="error" role="alert">' . e($error) . '</p>';
?>
<form method="post" enctype="multipart/form-data" class="form-card">
  <?= csrf_field() ?>
  <input type="hidden" name="MAX_FILE_SIZE" value="2097152">
  <label>Product name <input name="name" maxlength="180" required></label>
  <label>Category <input name="category" maxlength="100" required></label>
  <label>Description <textarea name="description" rows="4"></textarea></label>
  <label>Price <input type="number" name="price" min="0" step="0.01" required></label>
  <label>Stock <input type="number" name="stock" min="0" step="1" value="0" required></label>
  <label>Product photo (JPEG, PNG, or WebP; up to 2 MB) <input type="file" name="photo" accept="image/jpeg,image/png,image/webp"></label>
  <button type="submit">Save product</button>
</form>
<?php page_footer(); ?>
