<?php
// Demonstrate protection against SQL injection by comparing a vulnerable query with a prepared-statement version.
declare(strict_types=1);
require_once __DIR__ . '/02_database.php';
require_admin();
$example = "sample' OR '1'='1";
$vulnerable = "SELECT id, name FROM products WHERE name = '$example'";
$prepared = 'SELECT id, name FROM products WHERE name = :name';
page_header('SQL injection demonstration');
?>
<p>The string below is an inert example; the vulnerable statement is shown as escaped text and is never executed.</p>
<h2>Unsafe string-concatenated query</h2>
<pre><code><?= e($vulnerable) ?></code></pre>
<p>Joining user text into SQL changes the query's structure and can expose rows or alter data.</p>
<h2>Safe parameterized query</h2>
<pre><code><?= e($prepared) ?></code></pre>
<pre><code>$statement = db()-&gt;prepare('SELECT id, name FROM products WHERE name = :name');
$statement-&gt;execute(['name' =&gt; $userInput]);</code></pre>
<p>Bound values stay data. Placeholders cannot bind SQL identifiers; sort columns must use an allowlist, as in the <code>product_sort()</code> helper in <a href="13_product_sort.php">13_product_sort.php</a>.</p>
<?php page_footer(); ?>
