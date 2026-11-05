<?php
// Question 11: Validate and sanitize all user input using PHP filter_var() for emails, URLs, integers, and strings.
declare(strict_types=1);

function validate_email(mixed $value): string
{
    if (!is_string($value) || strlen($value) > 254) {
        throw new InvalidArgumentException('A valid email address is required.');
    }
    $email = filter_var(trim($value), FILTER_VALIDATE_EMAIL);
    if ($email === false) {
        throw new InvalidArgumentException('A valid email address is required.');
    }
    return strtolower($email);
}

function validate_url(mixed $value): string
{
    if (!is_string($value)) {
        throw new InvalidArgumentException('A valid HTTPS URL is required.');
    }
    $url = filter_var(trim($value), FILTER_VALIDATE_URL);
    if ($url === false || parse_url($url, PHP_URL_SCHEME) !== 'https') {
        throw new InvalidArgumentException('A valid HTTPS URL is required.');
    }
    return $url;
}

function validate_integer(mixed $value, int $min = 0, int $max = PHP_INT_MAX): int
{
    $result = filter_var($value, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => $min, 'max_range' => $max],
    ]);
    if ($result === false) {
        throw new InvalidArgumentException('An integer in the allowed range is required.');
    }
    return $result;
}

function validate_plain_string(mixed $value, int $maxLength = 255): string
{
    if (!is_string($value)) {
        throw new InvalidArgumentException('A text value is required.');
    }
    $value = trim(filter_var($value, FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW));
    if ($value === '' || strlen($value) > $maxLength || preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', $value)) {
        throw new InvalidArgumentException('Text is empty, too long, or contains control characters.');
    }
    return $value;
}

function validate_product(array $input): array
{
    $price = filter_var($input['price'] ?? null, FILTER_VALIDATE_FLOAT);
    if ($price === false || !is_finite((float) $price) || $price < 0 || $price > 99999999.99) {
        throw new InvalidArgumentException('A non-negative product price is required.');
    }
    return [
        'name' => validate_plain_string($input['name'] ?? null, 180),
        'description' => validate_plain_string($input['description'] ?? null, 5000),
        'price' => number_format((float) $price, 2, '.', ''),
    ];
}
