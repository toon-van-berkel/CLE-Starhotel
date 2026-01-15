<?php
declare(strict_types=1);

function log_error(Throwable $e): void
{
    $dir = dirname(__DIR__, 2) . '/storage/logs';
    if (!is_dir($dir)) mkdir($dir, 0777, true);

    $msg = sprintf(
        "[%s] %s in %s:%d\n%s\n\n",
        date('Y-m-d H:i:s'),
        $e->getMessage(),
        $e->getFile(),
        $e->getLine(),
        $e->getTraceAsString()
    );

    file_put_contents($dir . '/app.log', $msg, FILE_APPEND);
}
