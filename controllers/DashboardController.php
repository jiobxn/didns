<?php

class DashboardController {
    public function index() {
        // 检查用户是否已登录
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $username = $_SESSION['user'];
        require __DIR__ . '/../views/dashboard/index.php';
    }
}