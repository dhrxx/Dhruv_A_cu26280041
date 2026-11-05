<?php
// Question 12: Implement a login attempt limiter that locks an account for a fixed period after repeated failed logins.
declare(strict_types=1);

function authenticate_user(PDO $pdo, string $email, string $password): ?array
{
    $pdo->beginTransaction();
    try {
        $query = $pdo->prepare(
            'SELECT id, name, email, password_hash, failed_login_attempts, locked_until, email_verified_at
             FROM users WHERE email = :email FOR UPDATE'
        );
        $query->execute(['email' => $email]);
        $user = $query->fetch();
        if ($user === false) {
            $pdo->commit();
            return null;
        }

        if ($user['locked_until'] !== null && strtotime($user['locked_until'] . ' UTC') > time()) {
            $pdo->commit();
            return null;
        }
        if ($user['locked_until'] !== null) {
            $reset = $pdo->prepare(
                'UPDATE users SET failed_login_attempts = 0, locked_until = NULL WHERE id = :id'
            );
            $reset->execute(['id' => $user['id']]);
        }
        if (!password_verify($password, $user['password_hash']) || $user['email_verified_at'] === null) {
            $attempts = min(5, (int) $user['failed_login_attempts'] + 1);
            $lockUntil = $attempts >= 5 ? gmdate('Y-m-d H:i:s', time() + 900) : null;
            $update = $pdo->prepare(
                'UPDATE users SET failed_login_attempts = :attempts, locked_until = :locked WHERE id = :id'
            );
            $update->execute(['attempts' => $attempts, 'locked' => $lockUntil, 'id' => $user['id']]);
            $pdo->commit();
            return null;
        }

        $success = $pdo->prepare(
            'UPDATE users SET failed_login_attempts = 0, locked_until = NULL WHERE id = :id'
        );
        $success->execute(['id' => $user['id']]);
        $pdo->commit();
        return ['id' => (int) $user['id'], 'name' => $user['name'], 'email' => $user['email']];
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $exception;
    }
}
