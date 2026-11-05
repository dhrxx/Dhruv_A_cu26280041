<?php
require __DIR__ . '/db_connect.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Enter a name and valid email address.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO students (name, email) VALUES (:name, :email)');
        $stmt->execute(['name' => $name, 'email' => $email]);
        $message = 'Student added successfully.';
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><title>Add Student</title></head>
<body><h1>Add Student</h1>
<p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
<form method="post">
<label>Name <input name="name" maxlength="100" required></label><br>
<label>Email <input type="email" name="email" maxlength="254" required></label><br>
<button type="submit">Insert</button>
</form></body></html>
