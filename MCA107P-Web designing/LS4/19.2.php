<?php
require __DIR__ . '/db_connect.php';
try {
    $pdo->beginTransaction();
    $stmt = $pdo->prepare('INSERT INTO students (name, email) VALUES (?, ?)');
    $stmt->execute(['Aarav Sharma', 'aarav@example.com']);
    $id = (int)$pdo->lastInsertId();

    $profile = $pdo->prepare('INSERT INTO student_profiles (student_id, phone) VALUES (?, ?)');
    $profile->execute([$id, '9999999999']);

    $pdo->commit();
    echo 'Both records were saved successfully.';
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    error_log($e->getMessage());
    http_response_code(500);
    echo 'Transaction failed; changes were rolled back.';
}
