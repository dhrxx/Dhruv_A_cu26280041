<?php
// Question 5: Create an object-oriented Database class (PDO, singleton pattern) and model classes with reusable CRUD methods.
declare(strict_types=1);

final class Database
{
    private static ?self $instance = null;
    private PDO $connection;

    private function __construct()
    {
        $dsn = getenv('WEBAPP_DSN') ?: 'mysql:host=127.0.0.1;dbname=webapp_db;charset=utf8mb4';
        $user = getenv('WEBAPP_DB_USER');
        if (!is_string($user) || $user === '') {
            throw new RuntimeException('Set WEBAPP_DB_USER before starting the application.');
        }

        try {
            $this->connection = new PDO($dsn, $user, getenv('WEBAPP_DB_PASSWORD') ?: '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $exception) {
            error_log('Database connection failed: ' . $exception->getMessage());
            throw new RuntimeException('Database unavailable; check the server log and database settings.', 0, $exception);
        }
    }

    public static function instance(): self
    {
        return self::$instance ??= new self();
    }

    public function pdo(): PDO
    {
        return $this->connection;
    }

    private function __clone() {}
    public function __wakeup(): void
    {
        throw new LogicException('Database singleton cannot be unserialized.');
    }
}

final class Product
{
    public function __construct(private PDO $pdo) {}

    public function all(): array
    {
        return $this->pdo->query('SELECT id, name, description, price, image_path FROM products ORDER BY id DESC')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $statement = $this->pdo->prepare('SELECT id, name, description, price, image_path FROM products WHERE id = :id');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();
        return $row === false ? null : $row;
    }

    public function create(array $data): int
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO products (name, description, price) VALUES (:name, :description, :price)'
        );
        $statement->execute([
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $statement = $this->pdo->prepare(
            'UPDATE products SET name = :name, description = :description, price = :price WHERE id = :id'
        );
        $statement->execute([
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'id' => $id,
        ]);
        return $statement->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $statement = $this->pdo->prepare('DELETE FROM products WHERE id = :id');
        $statement->execute(['id' => $id]);
        return $statement->rowCount() > 0;
    }
}
