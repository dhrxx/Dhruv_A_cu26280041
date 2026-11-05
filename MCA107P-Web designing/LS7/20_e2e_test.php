<?php
// Test the complete end-to-end e-commerce user journey: browsing, searching, adding to cart, user login, checkout, and order confirmation.
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php';

if (PHP_SAPI === 'cli') {
    if (($argv[1] ?? '') === '--self-test') {
        final class TestBookstorePDO extends PDO
        {
            public array $books = [7 => ['id' => 7, 'title' => 'Test Book', 'price' => '12.50', 'stock' => 5]];
            public array $orders = [];
            public array $items = [];
            private bool $active = false;
            private int $nextOrderId = 1;
            private ?array $snapshot = null;

            public function __construct()
            {
            }

            public function beginTransaction(): bool
            {
                if ($this->active) {
                    return false;
                }
                $this->snapshot = [$this->books, $this->orders, $this->items, $this->nextOrderId];
                $this->active = true;
                return true;
            }

            public function inTransaction(): bool
            {
                return $this->active;
            }

            public function commit(): bool
            {
                $this->snapshot = null;
                $this->active = false;
                return true;
            }

            public function rollBack(): bool
            {
                if (!$this->active || $this->snapshot === null) {
                    return false;
                }
                [$this->books, $this->orders, $this->items, $this->nextOrderId] = $this->snapshot;
                $this->snapshot = null;
                $this->active = false;
                return true;
            }

            public function prepare(string $query, array $options = []): PDOStatement|false
            {
                return new TestBookstoreStatement($this, $query);
            }

            public function lastInsertId(?string $name = null): string|false
            {
                return (string) ($this->nextOrderId - 1);
            }

            public function insertOrder(array $values): void
            {
                $id = $this->nextOrderId++;
                $this->orders[$id] = [
                    'id' => $id,
                    'user_id' => (int) $values['user_id'],
                    'total' => (float) $values['total'],
                    'status' => 'Pending',
                ];
            }
        }

        final class TestBookstoreStatement extends PDOStatement
        {
            private mixed $row = false;

            public function __construct(
                private TestBookstorePDO $pdo,
                private string $query,
            ) {
            }

            public function execute(?array $params = null): bool
            {
                $params ??= [];
                if (str_starts_with($this->query, 'SELECT id, title, price, stock FROM books')) {
                    $this->row = $this->pdo->books[(int) $params['id']] ?? false;
                } elseif (str_starts_with($this->query, 'INSERT INTO orders')) {
                    $this->pdo->insertOrder($params);
                } elseif (str_starts_with($this->query, 'INSERT INTO order_items')) {
                    $this->pdo->items[] = $params;
                } elseif (str_starts_with($this->query, 'UPDATE books SET stock = stock -')) {
                    $this->pdo->books[(int) $params['id']]['stock'] -= (int) $params['quantity'];
                } else {
                    throw new RuntimeException('Unexpected SQL in checkout test: ' . $this->query);
                }
                return true;
            }

            public function fetch(int $mode = PDO::FETCH_DEFAULT, int $cursorOrientation = PDO::FETCH_ORI_NEXT, int $cursorOffset = 0): mixed
            {
                return $this->row;
            }
        }

        try {
            $pdo = new TestBookstorePDO();
            $shipping = [
                'name' => 'Test Customer',
                'address' => '1 Example Street',
                'city' => 'Test City',
                'postal_code' => '00000',
            ];
            $orderId = place_order($pdo, 42, [7 => 2], $shipping);
            if ($orderId !== 1 || $pdo->books[7]['stock'] !== 3 || $pdo->orders[1]['total'] !== 25.0
                || count($pdo->items) !== 1 || $pdo->inTransaction()) {
                throw new RuntimeException('Successful checkout did not save its order and stock changes.');
            }

            try {
                place_order($pdo, 42, [7 => 4], $shipping);
                throw new RuntimeException('Checkout accepted a quantity above remaining stock.');
            } catch (DomainException) {
            }
            if ($pdo->books[7]['stock'] !== 3 || count($pdo->orders) !== 1 || $pdo->inTransaction()) {
                throw new RuntimeException('Rejected checkout did not roll back its changes.');
            }

            $pdo->beginTransaction();
            place_order($pdo, 42, [7 => 1], $shipping);
            if (!$pdo->inTransaction() || $pdo->books[7]['stock'] !== 2) {
                throw new RuntimeException('Checkout did not respect an outer transaction.');
            }
            $pdo->rollBack();
            if ($pdo->books[7]['stock'] !== 3 || count($pdo->orders) !== 1) {
                throw new RuntimeException('Outer rollback did not restore checkout test state.');
            }

            echo "PASS: checkout creates correct order totals, items, and stock updates.\n";
            echo "PASS: over-stock orders are rejected and rolled back; caller transactions remain controllable.\n";
            exit(0);
        } catch (Throwable $exception) {
            fwrite(STDERR, 'FAIL: ' . $exception->getMessage() . PHP_EOL);
            exit(1);
        }
    }

    $pdo = db();
    $token = bin2hex(random_bytes(8));
    $email = 'bookstore-test-' . $token . '@example.invalid';
    $testPassword = bin2hex(random_bytes(16));
    $pdo->beginTransaction();
    try {
        $addUser = $pdo->prepare(
            'INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password)'
        );
        $addUser->execute([
            'name' => 'Bookstore Test',
            'email' => $email,
            'password' => password_hash($testPassword, PASSWORD_DEFAULT),
        ]);
        $userId = (int) $pdo->lastInsertId();
        $addBook = $pdo->prepare(
            'INSERT INTO books (title, author, category, description, price, stock)
             VALUES (:title, :author, :category, :description, :price, :stock)'
        );
        $addBook->execute([
            'title' => 'Temporary Checkout Test ' . $token,
            'author' => 'Automated Test',
            'category' => 'Testing',
            'description' => 'Temporary record rolled back by the test.',
            'price' => 12.50,
            'stock' => 5,
        ]);
        $bookId = (int) $pdo->lastInsertId();
        $passwordQuery = $pdo->prepare('SELECT password_hash FROM users WHERE id = :id');
        $passwordQuery->execute(['id' => $userId]);
        if (!password_verify($testPassword, $passwordQuery->fetchColumn())) {
            throw new RuntimeException('Password-hash check failed.');
        }

        $_SESSION['cart'] = [$bookId => 2];
        $search = $pdo->prepare('SELECT id FROM books WHERE title LIKE :title');
        $search->execute(['title' => '%' . $token . '%']);
        if ((int) $search->fetchColumn() !== $bookId) {
            throw new RuntimeException('Parameterized catalogue search failed.');
        }

        $orderId = place_order($pdo, $userId, $_SESSION['cart'], [
            'name' => 'Bookstore Test',
            'address' => '1 Example Street',
            'city' => 'Test City',
            'postal_code' => '00000',
        ]);
        $_SESSION['cart'] = [];
        $orderQuery = $pdo->prepare('SELECT total, status FROM orders WHERE id = :id AND user_id = :user_id');
        $orderQuery->execute(['id' => $orderId, 'user_id' => $userId]);
        $order = $orderQuery->fetch();
        if (!$order || (float) $order['total'] !== 25.0 || $order['status'] !== 'Pending') {
            throw new RuntimeException('Checkout order or confirmation data did not match.');
        }
        $stockQuery = $pdo->prepare('SELECT stock FROM books WHERE id = :id');
        $stockQuery->execute(['id' => $bookId]);
        if ((int) $stockQuery->fetchColumn() !== 3 || $_SESSION['cart'] !== []) {
            throw new RuntimeException('Checkout did not update inventory and clear the cart.');
        }

        try {
            place_order($pdo, $userId, [$bookId => 4], [
                'name' => 'Bookstore Test',
                'address' => '1 Example Street',
                'city' => 'Test City',
                'postal_code' => '00000',
            ]);
            throw new RuntimeException('Checkout allowed a quantity above remaining stock.');
        } catch (DomainException) {
        }

        $review = $pdo->prepare(
            'INSERT INTO reviews (book_id, user_id, rating, comment) VALUES (:book, :user, 5, :comment)'
        );
        $review->execute(['book' => $bookId, 'user' => $userId, 'comment' => 'A test review.']);
        $wishlist = $pdo->prepare('INSERT INTO wishlist (user_id, book_id) VALUES (:user, :book)');
        $wishlist->execute(['user' => $userId, 'book' => $bookId]);
        $statusUpdate = $pdo->prepare('UPDATE orders SET status = :status WHERE id = :id');
        $statusUpdate->execute(['status' => 'Shipped', 'id' => $orderId]);
        $report = $pdo->prepare(
            "SELECT COALESCE(SUM(total), 0) FROM orders WHERE user_id = :user AND status = 'Shipped'"
        );
        $report->execute(['user' => $userId]);
        if ((float) $report->fetchColumn() !== 25.0) {
            throw new RuntimeException('Sales report did not include the completed test order.');
        }

        $pdo->rollBack();
        echo "PASS: registration/password hashing, search, cart, checkout, stock guard, order confirmation, review, wishlist, order status, and sales report.\n";
        echo "PASS: all fixture records were rolled back; the database was left unchanged.\n";
        exit(0);
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        fwrite(STDERR, 'FAIL: ' . $exception->getMessage() . PHP_EOL);
        exit(1);
    }
}

bookstore_header('End-to-end journey test');

$checks = [
    ['Browse books', '03_books.php', 'Book cards load and in-stock books show an Add to cart button.'],
    ['Search books', '10_search.php?q=book', 'Title, author, and category matches are filtered.'],
    ['Add to cart', '13_stock_check.php', 'Submit Add to cart on any in-stock book; cart quantity cannot exceed stock.'],
    ['Register or sign in', '05_auth.php', 'Register a test account or sign in; the session ID is regenerated.'],
    ['Checkout', '06_cart.php', 'Open the cart and proceed; enter shipping details and place the order.'],
    ['Order confirmation', '12_order_history.php', 'A new Pending order appears; open its invoice and verify line items and total.'],
];
?>
<p>This end-to-end checklist uses the configured MySQL database and avoids making surprise test orders.</p>
<ol class="journey-checks">
    <?php foreach ($checks as [$label, $url, $expected]): ?>
        <li><a href="<?= e($url) ?>"><?= e($label) ?></a> — <?= e($expected) ?></li>
    <?php endforeach; ?>
</ol>
<p><strong>Pass criteria:</strong> complete each step without errors, verify a single order in order history, and confirm the purchased stock decreases.</p>
<?php bookstore_footer(); ?>
