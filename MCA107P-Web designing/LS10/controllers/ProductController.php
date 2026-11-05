<?php
declare(strict_types=1);

final class ProductController
{
    public function index(): void
    {
        $products = (new Product(db()))->all();
        render('products/index', ['products' => $products]);
    }

    public function apiCollection(string $method): never
    {
        (new ProductApiController(db()))->collection($method);
    }

    public function apiItem(string $method, int $id): never
    {
        (new ProductApiController(db()))->item($method, $id);
    }

    public function issueApiToken(): never
    {
        (new ProductApiController(db()))->issueToken();
    }
}
