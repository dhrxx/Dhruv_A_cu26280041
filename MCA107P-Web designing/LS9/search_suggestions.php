<?php
// Provide the prepared-statement JSON endpoint used by live_search.js.
declare(strict_types=1);
require_once __DIR__ . '/02_database.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
$query = $_GET['q'] ?? '';
if (!is_string($query)) {
    http_response_code(400);
    echo json_encode(['error' => 'Search text must be a single value.']);
    exit;
}
$query = trim($query);
if (strlen($query) < 2 || strlen($query) > 100) {
    echo json_encode([]);
    exit;
}
$statement = db()->prepare(
    'SELECT name FROM products WHERE name LIKE :query ORDER BY name LIMIT 8'
);
$statement->execute(['query' => '%' . $query . '%']);
$products = array_map(
    static fn (string $name): array => ['name' => $name],
    $statement->fetchAll(PDO::FETCH_COLUMN)
);
echo json_encode($products, JSON_THROW_ON_ERROR);
