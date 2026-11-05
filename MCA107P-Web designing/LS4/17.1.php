<?php
require __DIR__ . '/db_connect.php';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="students.csv"');
$out = fopen('php://output', 'w');
fputcsv($out, ['id','name','email','enrollment_date']);
$stmt = $pdo->query('SELECT id, name, email, enrollment_date FROM students ORDER BY id');
while ($row = $stmt->fetch(PDO::FETCH_NUM)) fputcsv($out, $row);
fclose($out);
exit;
