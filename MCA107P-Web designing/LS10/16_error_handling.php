<?php
// Question 16: Create custom 404 and 500 error pages, and configure PHP/Apache error logging while hiding errors from visitors.
declare(strict_types=1);

function configure_error_handling(): void
{
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    $log = getenv('WEBAPP_ERROR_LOG');
    if (is_string($log) && $log !== '') {
        ini_set('error_log', $log);
    }
    set_error_handler(static function (int $severity, string $message, string $file, int $line): bool {
        if (!(error_reporting() & $severity)) {
            return false;
        }
        throw new ErrorException($message, 0, $severity, $file, $line);
    });
}
