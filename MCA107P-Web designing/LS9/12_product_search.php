<?php
// Create a search and filter feature that retrieves products by name, category, or price range using LIKE and BETWEEN clauses.
declare(strict_types=1);

function product_search_filters(array $input): array
{
    $read = static function (string $key) use ($input): string {
        $value = $input[$key] ?? '';
        if (!is_string($value) && !is_int($value) && !is_float($value)) {
            throw new InvalidArgumentException('Search filters must be single values.');
        }
        return trim((string) $value);
    };
    $name = $read('name');
    $category = $read('category');
    $minimum = $read('min_price');
    $maximum = $read('max_price');
    foreach ([$name, $category] as $term) {
        if (strlen($term) > 100) {
            throw new InvalidArgumentException('Search text is limited to 100 characters.');
        }
    }
    $minPrice = $minimum === '' ? null : filter_var($minimum, FILTER_VALIDATE_FLOAT);
    $maxPrice = $maximum === '' ? null : filter_var($maximum, FILTER_VALIDATE_FLOAT);
    if (($minimum !== '' && ($minPrice === false || $minPrice < 0))
        || ($maximum !== '' && ($maxPrice === false || $maxPrice < 0))) {
        throw new InvalidArgumentException('Price filters must be non-negative numbers.');
    }
    if ($minPrice !== null && $maxPrice !== null && $minPrice > $maxPrice) {
        throw new InvalidArgumentException('Minimum price cannot exceed maximum price.');
    }
    return [
        'name' => $name, 'category' => $category,
        'min_price' => $minPrice, 'max_price' => $maxPrice,
    ];
}

function product_where(array $filters, array &$parameters): string
{
    $clauses = [];
    if ($filters['name'] !== '') {
        $clauses[] = 'name LIKE :name';
        $parameters['name'] = '%' . $filters['name'] . '%';
    }
    if ($filters['category'] !== '') {
        $clauses[] = 'category LIKE :category';
        $parameters['category'] = '%' . $filters['category'] . '%';
    }
    if ($filters['min_price'] !== null && $filters['max_price'] !== null) {
        $clauses[] = 'price BETWEEN :min_price AND :max_price';
        $parameters['min_price'] = number_format((float) $filters['min_price'], 2, '.', '');
        $parameters['max_price'] = number_format((float) $filters['max_price'], 2, '.', '');
    } elseif ($filters['min_price'] !== null) {
        $clauses[] = 'price >= :min_price';
        $parameters['min_price'] = number_format((float) $filters['min_price'], 2, '.', '');
    } elseif ($filters['max_price'] !== null) {
        $clauses[] = 'price <= :max_price';
        $parameters['max_price'] = number_format((float) $filters['max_price'], 2, '.', '');
    }
    return $clauses === [] ? '' : ' WHERE ' . implode(' AND ', $clauses);
}

function count_products(PDO $pdo, array $filters): int
{
    $parameters = [];
    $statement = $pdo->prepare('SELECT COUNT(*) FROM products' . product_where($filters, $parameters));
    $statement->execute($parameters);
    return (int) $statement->fetchColumn();
}

function find_products(PDO $pdo, array $filters, array $sort, int $limit, int $offset): array
{
    $parameters = [];
    $sql = 'SELECT id, name, category, description, price, stock, image_path FROM products'
        . product_where($filters, $parameters)
        . ' ORDER BY ' . $sort['column'] . ' ' . $sort['direction'] . ' LIMIT :limit OFFSET :offset';
    $statement = $pdo->prepare($sql);
    foreach ($parameters as $key => $value) {
        $statement->bindValue(':' . $key, $value);
    }
    $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
    $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetchAll();
}
