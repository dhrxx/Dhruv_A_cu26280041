<?php
// Prevent Cross-Site Scripting (XSS) by escaping all user-generated output using htmlspecialchars().
declare(strict_types=1);
require_once __DIR__ . '/02_database.php';
$sample = $_GET['text'] ?? '<script>alert("not executed")</script>';
if (!is_string($sample)) {
    $sample = '';
}
page_header('Escaped output example');
?>
<p>The input below is escaped before it is rendered as text:</p>
<pre><?= e($sample) ?></pre>
<p>Escaped output in an HTML text context: <code><?= e($sample) ?></code></p>
<p>Use <code>e()</code> for HTML text and quoted attributes; validate URLs and escape according to the actual output context. Never insert user-controlled content into executable JavaScript or CSS.</p>
<?php page_footer(); ?>
