<?php
$title = isset($title) && is_string($title) ? $title : 'Web Lab 10';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= escape($title) ?> | Lab Sheet 10</title>
  <link rel="stylesheet" href="/app.css">
</head>
<body>
<header>
  <nav aria-label="Main navigation">
    <a href="/">Products</a>
    <a href="/register">Register</a>
    <a href="/login">Login</a>
    <?php if (isset($_SESSION['user'])): ?>
      <form method="post" action="/logout" class="inline-form"><?= csrf_field() ?><button type="submit">Logout</button></form>
    <?php endif; ?>
  </nav>
</header>
<main class="container">
  <h1><?= escape($title) ?></h1>
