<?php
require __DIR__ . '/db_connect.php';
$rows = $pdo->query('SELECT id, name, email, enrollment_date FROM students ORDER BY id')->fetchAll();
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><title>Students</title>
<style>
body{font-family:Arial,sans-serif;margin:2rem;color:#222}
table{border-collapse:collapse;width:100%;max-width:900px}
th,td{padding:10px;border:1px solid #ccc;text-align:left}
th{background:#174a70;color:#fff}tr:nth-child(even){background:#f2f6f8}
</style></head><body><h1>Student Records</h1>
<table><thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Enrollment Date</th></tr></thead><tbody>
<?php foreach ($rows as $row): ?><tr>
<td><?= (int)$row['id'] ?></td>
<td><?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars($row['enrollment_date'], ENT_QUOTES, 'UTF-8') ?></td>
</tr><?php endforeach; ?>
</tbody></table></body></html>
