<?php
class Session {
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $val) {
        $_SESSION[$key] = $val;
    }

    public static function get($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : false;
    }

    public static function checkAdminSession() {
        self::init();
        if (self::get("adminLogin") == false) {
            self::destroy();
            header("Location:login.php");
            exit(); // Always include exit after a redirect
        }
    }

    public static function checkAdminLogin() {
        self::init();
        if (self::get("adminLogin") == true) {
            header("Location:index.php");
            exit(); // Always include exit after a redirect
        }
    }

    public static function checkSession() {
        self::init();
        if (self::get("login") == false) {
            self::destroy();
            header("Location:index.php");
            exit(); // Always include exit after a redirect
        }
    }

    public static function checkLogin() {
        self::init();
        if (self::get("login") == true) {
            header("Location:exam.php");
            exit(); // Always include exit after a redirect
        }
    }

    public static function destroy() {
        session_unset(); // Clear session data
        session_destroy(); // Destroy the session
    }
}
?>


