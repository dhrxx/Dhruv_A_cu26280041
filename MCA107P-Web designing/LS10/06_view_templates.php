<?php
// Question 6: Build reusable header, footer, and navigation templates using PHP include/require and pass data to views.
declare(strict_types=1);

function render(string $template, array $data = []): void
{
    $base = __DIR__ . '/views/';
    $file = realpath($base . $template . '.php');
    $root = realpath($base);
    if ($file === false || $root === false || !str_starts_with($file, $root . DIRECTORY_SEPARATOR)) {
        throw new RuntimeException('View template not found.');
    }
    extract($data, EXTR_SKIP);
    require $base . 'layouts/header.php';
    require $file;
    require $base . 'layouts/footer.php';
}

function escape(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
