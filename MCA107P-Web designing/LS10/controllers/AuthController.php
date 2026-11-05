<?php
declare(strict_types=1);

final class AuthController
{
    public function register(): void
    {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_valid_csrf();
            try {
                $name = validate_plain_string($_POST['name'] ?? null, 120);
                $email = validate_email($_POST['email'] ?? null);
                $password = $_POST['password'] ?? null;
                if (!is_string($password) || strlen($password) < 12 || strlen($password) > 4096) {
                    throw new InvalidArgumentException('Use a password of at least 12 characters.');
                }
                $pdo = db();
                $pdo->beginTransaction();
                try {
                    $insert = $pdo->prepare(
                        'INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :hash)'
                    );
                    $insert->execute([
                        'name' => $name,
                        'email' => $email,
                        'hash' => password_hash($password, PASSWORD_DEFAULT),
                    ]);
                    $userId = (int) $pdo->lastInsertId();
                    $token = bin2hex(random_bytes(32));
                    $confirmation = $pdo->prepare(
                        'INSERT INTO email_confirmation_tokens (user_id, token_hash, expires_at)
                         VALUES (:user_id, :hash, :expires)'
                    );
                    $confirmation->execute([
                        'user_id' => $userId,
                        'hash' => hash('sha256', $token),
                        'expires' => gmdate('Y-m-d H:i:s', time() + 86400),
                    ]);
                    $pdo->commit();
                } catch (Throwable $exception) {
                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    throw $exception;
                }
                $baseUrl = rtrim(getenv('APP_BASE_URL') ?: 'https://myproject.local', '/');
                send_application_email(
                    $email,
                    'Confirm your account',
                    "Confirm your account within 24 hours: {$baseUrl}/confirm-email?token={$token}"
                );
                render('auth/message', ['title' => 'Check your email', 'message' => 'Use the confirmation link to activate your account.']);
                return;
            } catch (PDOException $exception) {
                if ($exception->getCode() === '23000') {
                    $error = 'An account with that email already exists.';
                } else {
                    throw $exception;
                }
            } catch (InvalidArgumentException $exception) {
                $error = $exception->getMessage();
            }
        }
        render('auth/register', ['error' => $error]);
    }

    public function login(): void
    {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_valid_csrf();
            try {
                $email = validate_email($_POST['email'] ?? null);
                $password = $_POST['password'] ?? '';
                if (!is_string($password) || strlen($password) > 4096) {
                    throw new InvalidArgumentException('Email or password is incorrect.');
                }
                $user = authenticate_user(db(), $email, $password);
                if ($user === null) {
                    $error = 'Email or password is incorrect, the account is unverified, or sign-in is temporarily locked.';
                } else {
                    session_regenerate_id(true);
                    $_SESSION['user'] = $user;
                    redirect('/');
                }
            } catch (InvalidArgumentException $exception) {
                $error = $exception->getMessage();
            }
        }
        render('auth/login', ['error' => $error]);
    }

    public function logout(): never
    {
        require_valid_csrf();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', [
                'expires' => time() - 42000,
                'path' => $params['path'],
                'domain' => $params['domain'],
                'secure' => $params['secure'],
                'httponly' => $params['httponly'],
                'samesite' => 'Lax',
            ]);
        }
        session_destroy();
        redirect('/login');
    }

    public function confirmEmail(): void
    {
        $token = $_GET['token'] ?? '';
        if (!is_string($token) || !preg_match('/^[a-f0-9]{64}$/', $token)) {
            render('auth/message', ['title' => 'Invalid link', 'message' => 'This confirmation link is invalid or expired.']);
            return;
        }
        $pdo = db();
        $pdo->beginTransaction();
        $query = $pdo->prepare(
            'SELECT id, user_id FROM email_confirmation_tokens
             WHERE token_hash = :hash AND expires_at > UTC_TIMESTAMP() FOR UPDATE'
        );
        $query->execute(['hash' => hash('sha256', $token)]);
        $record = $query->fetch();
        if ($record === false) {
            $pdo->rollBack();
            render('auth/message', ['title' => 'Invalid link', 'message' => 'This confirmation link is invalid or expired.']);
            return;
        }
        $verify = $pdo->prepare('UPDATE users SET email_verified_at = UTC_TIMESTAMP() WHERE id = :id');
        $verify->execute(['id' => $record['user_id']]);
        $delete = $pdo->prepare('DELETE FROM email_confirmation_tokens WHERE user_id = :id');
        $delete->execute(['id' => $record['user_id']]);
        $pdo->commit();
        render('auth/message', ['title' => 'Email confirmed', 'message' => 'Your account is active. You can now sign in.']);
    }

    public function forgotPassword(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_valid_csrf();
            try {
                $email = validate_email($_POST['email'] ?? null);
                $query = db()->prepare('SELECT id FROM users WHERE email = :email AND email_verified_at IS NOT NULL');
                $query->execute(['email' => $email]);
                $userId = $query->fetchColumn();
                if ($userId !== false) {
                    $pdo = db();
                    $delete = $pdo->prepare('DELETE FROM password_reset_tokens WHERE user_id = :id');
                    $delete->execute(['id' => $userId]);
                    $token = create_password_reset($pdo, (int) $userId);
                    $baseUrl = rtrim(getenv('APP_BASE_URL') ?: 'https://myproject.local', '/');
                    send_application_email(
                        $email,
                        'Reset your password',
                        "Reset your password within one hour: {$baseUrl}/reset-password?token={$token}"
                    );
                }
            } catch (InvalidArgumentException) {
                // Use the same response for invalid and unknown addresses to reduce account enumeration.
            }
            render('auth/message', ['title' => 'Check your email', 'message' => 'If the address is registered, a reset link has been sent.']);
            return;
        }
        render('auth/forgot', []);
    }

    public function resetPassword(): void
    {
        $token = $_POST['token'] ?? $_GET['token'] ?? '';
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_valid_csrf();
            $password = $_POST['password'] ?? null;
            if (!is_string($password) || strlen($password) < 12 || strlen($password) > 4096) {
                $error = 'Use a password of at least 12 characters.';
            } elseif (!is_string($token) || !consume_password_reset(db(), $token, $password)) {
                $error = 'This reset link is invalid or expired.';
            } else {
                render('auth/message', ['title' => 'Password changed', 'message' => 'Your password was updated. Sign in with the new password.']);
                return;
            }
        }
        render('auth/reset', ['token' => is_string($token) ? $token : '', 'error' => $error]);
    }
}
