<?php
// Add pagination to the product listing page using LIMIT and OFFSET, with Previous/Next and numbered page links.
declare(strict_types=1);

function pagination_links(int $page, int $pageCount, array $query): string
{
    if ($pageCount <= 1) {
        return '';
    }
    $html = '<nav class="pagination" aria-label="Product pages">';
    if ($page > 1) {
        $params = $query;
        $params['page'] = $page - 1;
        $html .= '<a href="?' . e(http_build_query($params)) . '">Previous</a>';
    }
    for ($number = 1; $number <= $pageCount; $number++) {
        $params = $query;
        $params['page'] = $number;
        $current = $number === $page ? ' aria-current="page"' : '';
        $html .= '<a' . $current . ' href="?' . e(http_build_query($params)) . '">' . $number . '</a>';
    }
    if ($page < $pageCount) {
        $params = $query;
        $params['page'] = $page + 1;
        $html .= '<a href="?' . e(http_build_query($params)) . '">Next</a>';
    }
    return $html . '</nav>';
}
