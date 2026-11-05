<?php
session_start();
$_SESSION['visits'] = ($_SESSION['visits'] ?? 0) + 1;
$username = $_SESSION['username'] ?? 'Guest';
?>
<!doctype html><html lang="en"><meta charset="utf-8"><title>Session Demo</title><body>
<h1>Session Management</h1>
<p>Current user: <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?></p>
<p>This page has been opened <?= (int)$_SESSION['visits'] ?> time(s) in this session.</p>
</body></html>
