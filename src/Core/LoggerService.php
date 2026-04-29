<?php
namespace App\Core;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

class LoggerService {
    private static $logger = null;

    public static function getLogger() {
        if (self::$logger === null) {
            self::$logger = new Logger('portfolio_app');
            self::$logger->pushHandler(new StreamHandler(__DIR__ . '/../../logs/app.log', Logger::DEBUG));
            self::$logger->pushHandler(new FirePHPHandler());
        }
        return self::$logger;
    }

    public static function info($message, array $context = []) {
        self::getLogger()->info($message, $context);
    }

    public static function error($message, array $context = []) {
        self::getLogger()->error($message, $context);
    }
}
