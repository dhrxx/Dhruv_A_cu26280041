<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $theme = ($_POST['theme'] ?? '') === 'dark' ? 'dark' : 'light';
    setcookie('theme', $theme, [
        'expires'=>time()+30*24*60*60, 'path'=>'/',
        'secure'=>!empty($_SERVER['HTTPS']), 'httponly'=>true, 'samesite'=>'Lax'
    ]);
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}
$theme = ($_COOKIE['theme'] ?? 'light') === 'dark' ? 'dark' : 'light';
$bg = $theme === 'dark' ? '#222' : '#fff';
$fg = $theme === 'dark' ? '#fff' : '#222';
?>
<!doctype html><html lang="en"><meta charset="utf-8"><title>Theme Cookie</title>
<body style="font-family:Arial;background:<?= $bg ?>;color:<?= $fg ?>">
<h1>Cookie Preference</h1><p>Current theme: <?= htmlspecialchars($theme, ENT_QUOTES, 'UTF-8') ?></p>
<form method="post"><button name="theme" value="light">Light</button>
<button name="theme" value="dark">Dark</button></form></body></html>
