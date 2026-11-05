<?php
// Demo of password hashing and verification. A real app stores the hash in users.password_hash.
$plainPassword = 'Example-Password-123!';
$hash = password_hash($plainPassword, PASSWORD_DEFAULT);
echo '<p>Generated hash: ' . htmlspecialchars($hash, ENT_QUOTES, 'UTF-8') . '</p>';
if (password_verify('Example-Password-123!', $hash)) {
    echo 'Password verified successfully.';
} else {
    echo 'Password does not match.';
}
