<?php

class AuthMiddleware {
    public static function isAuthenticated() {
        return isset($_SESSION['user']);
    }

    public static function requireAuth() {
        if (!self::isAuthenticated()) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    public static function isAdminAuthenticated() {
        return isset($_SESSION['admin']);
    }

    public static function requireAdminAuth() {
        if (!self::isAdminAuthenticated()) {
            header('Location: ' . BASE_URL . '/admin/login');
            exit;
        }
    }
}