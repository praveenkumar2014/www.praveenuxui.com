<?php
namespace App\Helpers;

class RateLimiter {
    public static function check($key, $limit = 5, $period = 60) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $currentTime = time();
        $sessionKey = "rate_limit_" . md5($key);
        
        if (!isset($_SESSION[$sessionKey])) {
            $_SESSION[$sessionKey] = [
                'count' => 1,
                'start_time' => $currentTime
            ];
            return true;
        }
        
        $data = $_SESSION[$sessionKey];
        
        if ($currentTime - $data['start_time'] > $period) {
            $_SESSION[$sessionKey] = [
                'count' => 1,
                'start_time' => $currentTime
            ];
            return true;
        }
        
        if ($data['count'] < $limit) {
            $_SESSION[$sessionKey]['count']++;
            return true;
        }
        
        return false;
    }
}
