<?php
// Design a user review and rating system where registered users can leave star ratings and comments on book detail pages.
declare(strict_types=1);
require_once __DIR__ . '/_bootstrap.php';
$bookId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $user = require_login();
    $bookId = filter_var($_POST['book_id'] ?? null, FILTER_VALIDATE_INT);
    $rating = filter_var($_POST['rating'] ?? null, FILTER_VALIDATE_INT);
    $comment = trim((string) ($_POST['comment'] ?? ''));
    if (!$bookId || !$rating || $rating < 1 || $rating > 5 || $comment === '' || strlen($comment) > 2000) {
        set_flash('Choose 1–5 stars and enter a comment of up to 2,000 characters.');
    } else {
        $exists = db()->prepare('SELECT id FROM books WHERE id = :id');
        $exists->execute(['id' => $bookId]);
        if ($exists->fetchColumn() === false) {
            http_response_code(404);
            exit('Book not found.');
        }
        $save = db()->prepare(
            'INSERT INTO reviews (book_id, user_id, rating, comment)
             VALUES (:book_id, :user_id, :rating, :comment)
             ON DUPLICATE KEY UPDATE rating = VALUES(rating), comment = VALUES(comment), created_at = CURRENT_TIMESTAMP'
        );
        $save->execute([
            'book_id' => $bookId, 'user_id' => $user['id'],
            'rating' => $rating, 'comment' => $comment,
        ]);
        set_flash('Your review has been saved.');
    }
    redirect('04_book.php?id=' . (int) $bookId);
}

if (!$bookId) {
    echo '<p>Reviews are unavailable without a book.</p>';
    return;
}
$reviews = db()->prepare(
    'SELECT reviews.rating, reviews.comment, reviews.created_at, users.name
     FROM reviews JOIN users ON users.id = reviews.user_id
     WHERE reviews.book_id = :book_id ORDER BY reviews.created_at DESC'
);
$reviews->execute(['book_id' => $bookId]);
?>
<section class="reviews">
    <h2>Reader reviews</h2>
    <?php foreach ($reviews->fetchAll() as $review): ?>
        <article class="review">
            <p><strong><?= e($review['name']) ?></strong> · <?= str_repeat('★', (int) $review['rating']) ?><?= str_repeat('☆', 5 - (int) $review['rating']) ?></p>
            <p><?= nl2br(e($review['comment'])) ?></p><small><?= e($review['created_at']) ?></small>
        </article>
    <?php endforeach; ?>
    <?php if (current_user()): ?>
        <form method="post" data-validate><?= csrf_field() ?><input type="hidden" name="book_id" value="<?= (int) $bookId ?>">
            <label>Rating <select name="rating" required><option value="">Select stars</option>
                <?php for ($star = 5; $star >= 1; $star--): ?><option value="<?= $star ?>"><?= $star ?> star<?= $star === 1 ? '' : 's' ?></option><?php endfor; ?>
            </select></label>
            <label>Your review <textarea name="comment" maxlength="2000" required></textarea></label>
            <button type="submit">Post review</button>
        </form>
    <?php else: ?>
        <p><a href="05_auth.php">Sign in</a> to write a review.</p>
    <?php endif; ?>
</section>
