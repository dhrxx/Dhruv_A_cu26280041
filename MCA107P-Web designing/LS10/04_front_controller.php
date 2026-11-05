<?php
// Question 4: Implement a front controller (index.php) with a simple router that maps URLs to controller actions.
declare(strict_types=1);

require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/ProductController.php';

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$path = is_string($path) ? '/' . trim($path, '/') : '/';
$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

try {
    if ($path === '/' || $path === '/products') {
        (new ProductController())->index();
    } elseif ($path === '/login' && in_array($method, ['GET', 'POST'], true)) {
        (new AuthController())->login();
    } elseif ($path === '/register' && in_array($method, ['GET', 'POST'], true)) {
        (new AuthController())->register();
    } elseif ($path === '/logout' && $method === 'POST') {
        (new AuthController())->logout();
    } elseif ($path === '/forgot-password' && in_array($method, ['GET', 'POST'], true)) {
        (new AuthController())->forgotPassword();
    } elseif ($path === '/reset-password' && in_array($method, ['GET', 'POST'], true)) {
        (new AuthController())->resetPassword();
    } elseif ($path === '/confirm-email' && $method === 'GET') {
        (new AuthController())->confirmEmail();
    } elseif ($path === '/api/token' && $method === 'POST') {
        (new ProductController())->issueApiToken();
    } elseif ($path === '/api/products') {
        (new ProductController())->apiCollection($method);
    } elseif (preg_match('~^/api/products/([1-9][0-9]*)$~', $path, $match)) {
        (new ProductController())->apiItem($method, (int) $match[1]);
    } elseif ($path === '/upload' && in_array($method, ['GET', 'POST'], true)) {
        require __DIR__ . '/15_upload.php';
    } else {
        http_response_code(404);
        require __DIR__ . '/views/errors/404.php';
    }
} catch (InvalidArgumentException $exception) {
    http_response_code(400);
    if (str_starts_with($path, '/api/')) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => $exception->getMessage()], JSON_INVALID_UTF8_SUBSTITUTE);
    } else {
        render('errors/page', ['title' => 'Invalid request', 'message' => $exception->getMessage()]);
    }
} catch (HttpException $exception) {
    http_response_code($exception->statusCode);
    if (str_starts_with($path, '/api/')) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => $exception->getMessage()], JSON_INVALID_UTF8_SUBSTITUTE);
    } else {
        render('errors/page', [
            'title' => $exception->statusCode === 400 ? 'Bad request' : 'Request rejected',
            'message' => $exception->getMessage(),
        ]);
    }
} catch (Throwable $exception) {
    error_log((string) $exception);
    http_response_code(500);
    require __DIR__ . '/views/errors/500.php';
}
