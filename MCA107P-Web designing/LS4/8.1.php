<?php
require __DIR__ . '/db_connect.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $email = trim($_POST['email'] ?? '');
    if (!$id || $id < 1 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Enter a valid student ID and email.';
    } else {
        $stmt = $pdo->prepare('UPDATE students SET email = :email WHERE id = :id');
        $stmt->execute(['email' => $email, 'id' => $id]);
        $message = $stmt->rowCount() ? 'Email updated.' : 'No record changed; verify the ID/email.';
    }
}
?>
<!doctype html><html lang="en"><meta charset="utf-8"><title>Update Email</title><body>
<h1>Update Student Email</h1><p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
<form method="post"><label>Student ID <input type="number" name="id" min="1" required></label><br>
<label>New email <input type="email" name="email" required></label><br><button>Update</button></form>
</body></html>
