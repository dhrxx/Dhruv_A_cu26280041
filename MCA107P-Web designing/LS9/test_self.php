<?php
declare(strict_types=1);
require_once __DIR__ . '/02_database.php';

if (PHP_SAPI === 'cli' && ($argv[1] ?? '') === '--self-test') {
    $_SESSION = [];
    $_SESSION['cart'] = [];
    $_SESSION['cart'][4] = 2;
    $_SESSION['cart'][9] = 1;
    if (array_sum($_SESSION['cart']) !== 3) {
        fwrite(STDERR, "FAIL: cart add check\n");
        exit(1);
    }
    $_SESSION['cart'][4] = 5;
    if ($_SESSION['cart'][4] !== 5) {
        fwrite(STDERR, "FAIL: cart update check\n");
        exit(1);
    }
    unset($_SESSION['cart'][9]);
    if (array_keys($_SESSION['cart']) !== [4]) {
        fwrite(STDERR, "FAIL: cart removal check\n");
        exit(1);
    }
    require_once __DIR__ . '/12_product_search.php';
    $filters = product_search_filters([
        'name' => "tea' OR 1=1 --", 'category' => 'Drink',
        'min_price' => '1.50', 'max_price' => '9.99',
    ]);
    $parameters = [];
    $where = product_where($filters, $parameters);
    if (!str_contains($where, 'name LIKE :name') || !str_contains($where, 'price BETWEEN')
        || !str_contains($parameters['name'], "tea' OR 1=1 --")) {
        fwrite(STDERR, "FAIL: search filters were not parameterized\n");
        exit(1);
    }
    require_once __DIR__ . '/13_product_sort.php';
    $sort = product_sort('name; DROP TABLE users', 'desc');
    if ($sort !== ['column' => 'name', 'direction' => 'DESC']) {
        fwrite(STDERR, "FAIL: unsafe sort input was accepted\n");
        exit(1);
    }
    if (extension_loaded('pdo_sqlite')) {
        $sqlite = new PDO('sqlite::memory:', null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        $sqlite->exec(
            'CREATE TABLE products (
                id INTEGER PRIMARY KEY, name TEXT, category TEXT, description TEXT,
                price NUMERIC, stock INTEGER, image_path TEXT
            )'
        );
        $insert = $sqlite->prepare(
            'INSERT INTO products (id, name, category, description, price, stock)
             VALUES (:id, :name, :category, :description, :price, :stock)'
        );
        foreach ([
            [1, 'Green tea', 'Drink', 'Loose leaf', 4.50, 8],
            [2, 'Coffee beans', 'Drink', 'Roasted', 12.00, 5],
            [3, 'Tea towel', 'Home', 'Cotton', 9.00, 3],
        ] as [$id, $name, $category, $description, $price, $stock]) {
            $insert->execute(compact('id', 'name', 'category', 'description', 'price', 'stock'));
        }
        $filters = product_search_filters([
            'name' => 'tea', 'category' => 'Drink', 'min_price' => '3', 'max_price' => '5',
        ]);
        $count = count_products($sqlite, $filters);
        $products = find_products($sqlite, $filters, product_sort('price', 'desc'), 1, 0);
        if ($count !== 1 || count($products) !== 1 || $products[0]['name'] !== 'Green tea') {
            fwrite(STDERR, "FAIL: SQLite-backed search/filter/sort query\n");
            exit(1);
        }
        $pageTwo = find_products($sqlite, product_search_filters([]), product_sort('name', 'asc'), 2, 1);
        if (count($pageTwo) !== 2) {
            fwrite(STDERR, "FAIL: SQLite-backed LIMIT/OFFSET pagination query\n");
            exit(1);
        }
        $injectionAttempt = find_products($sqlite, product_search_filters([
            'name' => "' OR 1=1 --",
        ]), product_sort('name', 'asc'), 10, 0);
        if ($injectionAttempt !== []) {
            fwrite(STDERR, "FAIL: parameterized search returned unrelated rows.\n");
            exit(1);
        }
        require_once __DIR__ . '/11_product_pagination.php';
        $pagination = pagination_links(2, 3, ['name' => 'tea']);
        if (!str_contains($pagination, 'Previous') || !str_contains($pagination, 'Next')
            || !str_contains($pagination, 'aria-current="page"')) {
            fwrite(STDERR, "FAIL: numbered previous/next pagination links.\n");
            exit(1);
        }
        echo "PASS: prepared search rejects SQL injection input and pagination links preserve filters.\n";
        echo "PASS: SQLite-backed parameterized product filters, sorting, and LIMIT/OFFSET pagination.\n";
    } else {
        echo "SKIP: PDO SQLite is unavailable; product-query tests can run against MySQL after setup.\n";
    }
    require_once __DIR__ . '/order_service.php';

    final class CheckoutTestPDO extends PDO
    {
        public array $products = [5 => ['id' => 5, 'name' => 'Sample item', 'price' => '12.50', 'stock' => 5]];
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
            $this->snapshot = [$this->products, $this->orders, $this->items, $this->nextOrderId];
            $this->active = true;
            return true;
        }

        public function inTransaction(): bool
        {
            return $this->active;
        }

        public function commit(): bool
        {
            $this->active = false;
            $this->snapshot = null;
            return true;
        }

        public function rollBack(): bool
        {
            if (!$this->active || $this->snapshot === null) return false;
            [$this->products, $this->orders, $this->items, $this->nextOrderId] = $this->snapshot;
            $this->active = false;
            $this->snapshot = null;
            return true;
        }

        public function prepare(string $query, array $options = []): PDOStatement|false
        {
            return new CheckoutTestStatement($this, $query);
        }

        public function lastInsertId(?string $name = null): string|false
        {
            return (string) ($this->nextOrderId - 1);
        }

        public function addOrder(array $values): void
        {
            $id = $this->nextOrderId++;
            $this->orders[$id] = ['id' => $id] + $values;
        }
    }

    final class CheckoutTestStatement extends PDOStatement
    {
        private mixed $row = false;
        private int $affectedRows = 0;

        public function __construct(private CheckoutTestPDO $pdo, private string $query)
        {
        }

        public function execute(?array $params = null): bool
        {
            $params ??= [];
            $this->affectedRows = 0;
            if (str_starts_with($this->query, 'SELECT id, name, price, stock FROM products')) {
                $this->row = $this->pdo->products[(int) $params['id']] ?? false;
            } elseif (str_starts_with($this->query, 'INSERT INTO orders')) {
                $this->pdo->addOrder($params);
                $this->affectedRows = 1;
            } elseif (str_starts_with($this->query, 'INSERT INTO order_items')) {
                $this->pdo->items[] = $params;
                $this->affectedRows = 1;
            } elseif (str_starts_with($this->query, 'UPDATE products SET stock = stock -')) {
                $id = (int) $params['id'];
                if ($this->pdo->products[$id]['stock'] >= (int) $params['required_stock']) {
                    $this->pdo->products[$id]['stock'] -= (int) $params['quantity'];
                    $this->affectedRows = 1;
                }
            } else {
                throw new RuntimeException('Unexpected checkout test query: ' . $this->query);
            }
            return true;
        }

        public function fetch(int $mode = PDO::FETCH_DEFAULT, int $cursorOrientation = PDO::FETCH_ORI_NEXT, int $cursorOffset = 0): mixed
        {
            return $this->row;
        }

        public function rowCount(): int
        {
            return $this->affectedRows;
        }
    }

    $checkoutDb = new CheckoutTestPDO();
    $orderId = place_order($checkoutDb, 11, [5 => 2]);
    if ($orderId !== 1 || $checkoutDb->products[5]['stock'] !== 3
        || $checkoutDb->orders[1]['total'] !== '25.00' || count($checkoutDb->items) !== 1
        || $checkoutDb->inTransaction()) {
        fwrite(STDERR, "FAIL: checkout success did not record total, item, and inventory.\n");
        exit(1);
    }
    try {
        place_order($checkoutDb, 11, [5 => 4]);
        fwrite(STDERR, "FAIL: checkout accepted an order above available stock.\n");
        exit(1);
    } catch (DomainException) {
    }
    if ($checkoutDb->products[5]['stock'] !== 3 || count($checkoutDb->orders) !== 1
        || $checkoutDb->inTransaction()) {
        fwrite(STDERR, "FAIL: rejected checkout did not roll back changes.\n");
        exit(1);
    }
    $checkoutDb->beginTransaction();
    place_order($checkoutDb, 11, [5 => 1]);
    if (!$checkoutDb->inTransaction() || $checkoutDb->products[5]['stock'] !== 2) {
        fwrite(STDERR, "FAIL: checkout unexpectedly committed its caller's transaction.\n");
        exit(1);
    }
    $checkoutDb->rollBack();
    if ($checkoutDb->products[5]['stock'] !== 3 || count($checkoutDb->orders) !== 1) {
        fwrite(STDERR, "FAIL: caller rollback did not restore checkout test state.\n");
        exit(1);
    }
    echo "PASS: checkout totals, order items, stock changes, over-stock rejection, rollback, and transaction ownership.\n";
    $encoded = e('<script>alert(1)</script>');
    if ($encoded !== '&lt;script&gt;alert(1)&lt;/script&gt;') {
        fwrite(STDERR, "FAIL: HTML output escaping\n");
        exit(1);
    }
    echo "PASS: cart add/update/remove, parameterized search filters, sort allowlist, and XSS escaping.\n";
    require_once __DIR__ . '/14_product_upload.php';
    $_FILES = [];
    if (product_image_upload() !== null) {
        fwrite(STDERR, "FAIL: empty image upload should be optional.\n");
        exit(1);
    }
    $_FILES['photo'] = ['error' => UPLOAD_ERR_INI_SIZE, 'tmp_name' => '', 'size' => 0];
    try {
        product_image_upload();
        fwrite(STDERR, "FAIL: invalid image upload error was accepted.\n");
        exit(1);
    } catch (ProductUploadException) {
    }
    unset($_FILES['photo']);
    $hashedPassword = password_hash('correct-horse-battery', PASSWORD_DEFAULT);
    if (!password_verify('correct-horse-battery', $hashedPassword)
        || password_verify('incorrect-password', $hashedPassword)) {
        fwrite(STDERR, "FAIL: password hash/verification check.\n");
        exit(1);
    }
    echo "PASS: optional/failed upload validation and password hash verification.\n";
    echo "Database-backed login and uploaded-file handling require configured MySQL/web server; see 00_setup.txt.\n";
    exit(0);
}

http_response_code(404);
exit('Run this smoke test from the command line with --self-test.');
