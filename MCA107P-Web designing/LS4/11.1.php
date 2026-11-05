<?php
$keys = ['SERVER_NAME','SERVER_ADDR','SERVER_PORT','SERVER_SOFTWARE',
         'DOCUMENT_ROOT','REQUEST_METHOD','REQUEST_URI','HTTP_HOST'];
echo '<h1>Apache Server Details</h1><dl>';
foreach ($keys as $key) {
    $value = $_SERVER[$key] ?? 'Not available';
    echo '<dt><b>' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '</b></dt>';
    echo '<dd>' . htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8') . '</dd>';
}
echo '</dl>';
