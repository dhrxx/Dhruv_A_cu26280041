<?php
// Implement column sorting (ascending/descending) on the product table by clicking the table headers.
declare(strict_types=1);

function product_sort(mixed $column, mixed $direction): array
{
    $allowed = [
        'name' => 'name',
        'category' => 'category',
        'price' => 'price',
        'stock' => 'stock',
    ];
    $column = is_string($column) && isset($allowed[$column]) ? $allowed[$column] : 'name';
    $direction = is_string($direction) && strtolower($direction) === 'desc' ? 'DESC' : 'ASC';
    return ['column' => $column, 'direction' => $direction];
}

function sort_url(string $column, array $sort, array $query): string
{
    $query['sort'] = $column;
    $query['direction'] = $sort['column'] === $column && $sort['direction'] === 'ASC' ? 'desc' : 'asc';
    $query['page'] = 1;
    return '?' . http_build_query($query);
}
