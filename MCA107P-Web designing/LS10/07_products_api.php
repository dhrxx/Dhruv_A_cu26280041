<?php
// Question 7: Develop a RESTful JSON API in PHP supporting GET, POST, PUT, and DELETE operations on the products table.
declare(strict_types=1);

final class ProductApiController
{
    public function __construct(private PDO $pdo) {}

    public function collection(string $method): never
    {
        $isAdmin = $this->authorize();
        if ($method === 'GET') {
            $this->respond($this->pdo->query(
                'SELECT id, name, description, price FROM products ORDER BY id DESC'
            )->fetchAll());
        }
        if ($method !== 'POST') {
            $this->methodNotAllowed(['GET', 'POST']);
        }
        if (!$isAdmin) {
            $this->respond(['error' => 'Administrator access required.'], 403);
        }
        $data = $this->jsonInput();
        $valid = validate_product($data);
        $statement = $this->pdo->prepare(
            'INSERT INTO products (name, description, price) VALUES (:name, :description, :price)'
        );
        $statement->execute($valid);
        http_response_code(201);
        $this->respond(['id' => (int) $this->pdo->lastInsertId()]);
    }

    public function item(string $method, int $id): never
    {
        $isAdmin = $this->authorize();
        if ($method === 'GET') {
            $statement = $this->pdo->prepare(
                'SELECT id, name, description, price FROM products WHERE id = :id'
            );
            $statement->execute(['id' => $id]);
            $product = $statement->fetch();
            if ($product === false) {
                $this->respond(['error' => 'Product not found.'], 404);
            }
            $this->respond($product);
        }
        if (!$isAdmin) {
            $this->respond(['error' => 'Administrator access required.'], 403);
        }
        if ($method === 'DELETE') {
            $statement = $this->pdo->prepare('DELETE FROM products WHERE id = :id');
            $statement->execute(['id' => $id]);
            if ($statement->rowCount() === 0) {
                $this->respond(['error' => 'Product not found.'], 404);
            }
            $this->respond(['deleted' => true]);
        }
        if ($method === 'PUT') {
            $valid = validate_product($this->jsonInput());
            $statement = $this->pdo->prepare(
                'UPDATE products SET name = :name, description = :description, price = :price WHERE id = :id'
            );
            $statement->execute($valid + ['id' => $id]);
            if ($statement->rowCount() === 0 && $this->productDoesNotExist($id)) {
                $this->respond(['error' => 'Product not found.'], 404);
            }
            $this->respond(['updated' => true]);
        }
        $this->methodNotAllowed(['GET', 'PUT', 'DELETE']);
    }

    public function issueToken(): never
    {
        $input = $this->jsonInput();
        $email = filter_var($input['email'] ?? null, FILTER_VALIDATE_EMAIL);
        $password = $input['password'] ?? null;
        if (!is_string($email) || !is_string($password)) {
            $this->respond(['error' => 'Valid email and password are required.'], 400);
        }
        $statement = $this->pdo->prepare(
            'SELECT id, password_hash, email_verified_at FROM users WHERE email = :email'
        );
        $statement->execute(['email' => $email]);
        $user = $statement->fetch();
        if ($user === false || $user['email_verified_at'] === null
            || !password_verify($password, $user['password_hash'])) {
            $this->respond(['error' => 'Invalid credentials.'], 401);
        }
        $token = bin2hex(random_bytes(32));
        $store = $this->pdo->prepare(
            'INSERT INTO api_tokens (user_id, token_hash, expires_at) VALUES (:user_id, :hash, :expires)'
        );
        $store->execute([
            'user_id' => $user['id'],
            'hash' => hash('sha256', $token),
            'expires' => gmdate('Y-m-d H:i:s', time() + 3600),
        ]);
        $this->respond(['token' => $token, 'expires_in' => 3600], 201);
    }

    private function authorize(): bool
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (!preg_match('/^Bearer ([a-f0-9]{64})$/', $header, $match)) {
            $this->respond(['error' => 'Bearer token required.'], 401);
        }
        $query = $this->pdo->prepare(
            'SELECT users.role FROM api_tokens
             JOIN users ON users.id = api_tokens.user_id
             WHERE api_tokens.token_hash = :hash AND api_tokens.expires_at > :now'
        );
        $query->execute([
            'hash' => hash('sha256', $match[1]),
            'now' => gmdate('Y-m-d H:i:s'),
        ]);
        $user = $query->fetch();
        if ($user === false) {
            $this->respond(['error' => 'Invalid or expired token.'], 401);
        }
        return $user['role'] === 'admin';
    }

    private function productDoesNotExist(int $id): bool
    {
        $query = $this->pdo->prepare('SELECT 1 FROM products WHERE id = :id');
        $query->execute(['id' => $id]);
        return $query->fetchColumn() === false;
    }

    private function jsonInput(): array
    {
        try {
            $data = json_decode(file_get_contents('php://input') ?: '', true, 32, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            $this->respond(['error' => 'Request body must be valid JSON.'], 400);
        }
        if (!is_array($data)) {
            $this->respond(['error' => 'JSON object required.'], 400);
        }
        return $data;
    }

    private function methodNotAllowed(array $allowed): never
    {
        header('Allow: ' . implode(', ', $allowed));
        $this->respond(['error' => 'Method not allowed.'], 405);
    }

    private function respond(array $body, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($body, JSON_THROW_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE);
        exit;
    }
}
