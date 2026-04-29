<?php
namespace App\Core;

class ErrorHandler {
    public static function register() {
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    public static function handleError($errno, $errstr, $errfile, $errline) {
        LoggerService::error("Error [$errno]: $errstr in $errfile on line $errline");
        if (!(error_reporting() & $errno)) return;
        throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    public static function handleException($exception) {
        LoggerService::error("Exception: " . $exception->getMessage(), [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ]);

        if (php_sapi_name() !== 'cli') {
            if (!headers_sent()) {
                header('Content-Type: application/json', true, 500);
            }
            echo json_encode(['error' => 'An internal server error occurred.']);
        }
        exit(1);
    }

    public static function handleShutdown() {
        $error = error_get_last();
        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            self::handleError($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }
}
