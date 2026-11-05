<?php
// Design the homepage of the online bookstore featuring featured books, search bar, category navigation, and promotional banners using HTML and CSS.
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php';

$featured = db()->query(
    'SELECT id, title, author, category, price, stock, image
     FROM books WHERE stock > 0 ORDER BY created_at DESC LIMIT 4'
)->fetchAll();
$categories = db()->query('SELECT DISTINCT category FROM books ORDER BY category')->fetchAll(PDO::FETCH_COLUMN);

bookstore_header('Find your next favorite');
?>
<section class="hero">
    <p class="eyebrow">A little more wonder on every page</p>
    <h2>Stories worth staying up for.</h2>
    <p>Explore thoughtful picks from independent authors and beloved classics.</p>
    <form class="search-form" action="10_search.php" method="get">
        <label for="home-search">Search by title, author, or category</label>
        <input id="home-search" type="search" name="q" placeholder="Try a favorite author">
        <button type="submit">Search books</button>
    </form>
</section>
<aside class="promotion"><strong>Bookshop weekend:</strong> Free shipping on orders over $50.</aside>
<section>
    <h2>Browse categories</h2>
    <div class="category-list">
        <?php foreach ($categories as $category): ?>
            <a class="category-chip" href="10_search.php?category=<?= rawurlencode((string) $category) ?>"><?= e($category) ?></a>
        <?php endforeach; ?>
        <?php if ($categories === []): ?><p>Categories will appear as books are added.</p><?php endif; ?>
    </div>
</section>
<section>
    <h2>Featured books</h2>
    <div class="book-grid">
        <?php foreach ($featured as $book): book_card($book); endforeach; ?>
        <?php if ($featured === []): ?><p>Add books to the catalogue to feature them here.</p><?php endif; ?>
    </div>
</section>
<?php bookstore_footer(); ?>
