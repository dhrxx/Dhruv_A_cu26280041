<?php
// Create a checkout script that saves the cart contents into the orders table using a database transaction (BEGIN, COMMIT, ROLLBACK).
declare(strict_types=1);
require_once __DIR__ . '/02_database.php';

function place_order(PDO $pdo, int $userId, array $cart): int
{
    if ($userId < 1 || $cart === []) {
        throw new DomainException('Sign in and add products before placing an order.');
    }

    $ownsTransaction = !$pdo->inTransaction();
    if ($ownsTransaction) {
        $pdo->beginTransaction();
    }
    try {
        $lockedProducts = [];
        $totalCents = 0;
        $select = $pdo->prepare(
            'SELECT id, name, price, stock FROM products WHERE id = :id FOR UPDATE'
        );
        foreach ($cart as $productId => $quantity) {
            $quantity = filter_var($quantity, FILTER_VALIDATE_INT);
            if (!$quantity || $quantity < 1) {
                throw new DomainException('The cart contains an invalid quantity.');
            }
            $select->execute(['id' => (int) $productId]);
            $product = $select->fetch();
            if (!$product || $quantity > (int) $product['stock']) {
                throw new DomainException('A product is no longer available in the requested quantity.');
            }
            $totalCents += (int) round((float) $product['price'] * 100) * $quantity;
            $lockedProducts[] = [$product, $quantity];
        }

        $order = $pdo->prepare(
            'INSERT INTO orders (user_id, total, status) VALUES (:user_id, :total, :status)'
        );
        $order->execute([
            'user_id' => $userId,
            'total' => intdiv($totalCents, 100) . '.' . str_pad((string) ($totalCents % 100), 2, '0', STR_PAD_LEFT),
            'status' => 'Pending',
        ]);
        $orderId = (int) $pdo->lastInsertId();
        $itemInsert = $pdo->prepare(
            'INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price)
             VALUES (:order_id, :product_id, :product_name, :quantity, :unit_price)'
        );
        $stockUpdate = $pdo->prepare(
            'UPDATE products SET stock = stock - :quantity WHERE id = :id AND stock >= :required_stock'
        );
        foreach ($lockedProducts as [$product, $quantity]) {
            $itemInsert->execute([
                'order_id' => $orderId, 'product_id' => $product['id'],
                'product_name' => $product['name'], 'quantity' => $quantity,
                'unit_price' => $product['price'],
            ]);
            $stockUpdate->execute([
                'quantity' => $quantity, 'id' => $product['id'], 'required_stock' => $quantity,
            ]);
            if ($stockUpdate->rowCount() !== 1) {
                throw new DomainException('Inventory changed; please check your cart and retry.');
            }
        }
        if ($ownsTransaction) {
            $pdo->commit();
        }
        return $orderId;
    } catch (Throwable $exception) {
        if ($ownsTransaction && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $exception;
    }
}
