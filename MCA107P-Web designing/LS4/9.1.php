<?php
require __DIR__ . '/db_connect.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$message = 'Provide a valid student ID.';
if ($id && $id > 0) {
    // GET is used to match the lab task; real applications should use POST and CSRF protection.
    $stmt = $pdo->prepare('DELETE FROM students WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $message = $stmt->rowCount() ? 'Student deleted.' : 'No student found for that ID.';
}
?>
<!doctype html><html lang="en"><meta charset="utf-8"><title>Delete Student</title><body>
<p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
<p>Example: delete_student.php?id=1</p></body></html>
