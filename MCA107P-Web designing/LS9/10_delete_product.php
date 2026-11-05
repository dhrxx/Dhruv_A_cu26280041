<?php
// Implement record deletion with a JavaScript confirmation prompt and a secure PHP DELETE handler.
declare(strict_types=1);
require_once __DIR__ . '/02_database.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Use the product-list delete form to remove an item.');
}
verify_csrf();
$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(400);
    exit('A valid product ID is required.');
}
$statement = db()->prepare('DELETE FROM products WHERE id = :id');
$statement->execute(['id' => $id]);
$_SESSION['flash'] = $statement->rowCount() ? 'Product deleted.' : 'Product was not found.';
header('Location: 08_products.php', true, 303);
exit;
