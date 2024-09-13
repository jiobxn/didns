<?php

require_once '../config/config.php';
require_once '../lib/AuthMiddleware.php';

session_start();

// 简单的路由系统
$request = $_SERVER['REQUEST_URI'];
$basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
$route = str_replace($basePath, '', parse_url($request, PHP_URL_PATH));
$query = [];
parse_str(parse_url($request, PHP_URL_QUERY), $query);

switch ($route) {
    case '/':
    case '/dns':
        AuthMiddleware::requireAdminAuth();
        require '../controllers/DnsController.php';
        $controller = new DnsController();
        $controller->index();
        break;
    case '/dns/add':
        AuthMiddleware::requireAdminAuth();
        require '../controllers/DnsController.php';
        $controller = new DnsController();
        $controller->add($query);
        break;
    case '/dns/delete':
        AuthMiddleware::requireAdminAuth();
        require '../controllers/DnsController.php';
        $controller = new DnsController();
        $controller->delete();
        break;
    case '/admin/login':
        require '../controllers/AuthController.php';
        $controller = new AuthController();
        $controller->adminLogin();
        break;
    case '/login':
        require '../controllers/AuthController.php';
        $controller = new AuthController();
        $controller->clientLogin();
        break;
    case '/verify':
        require '../controllers/AuthController.php';
        $controller = new AuthController();
        $controller->verify();
        break;
    case '/dashboard':
        AuthMiddleware::requireAuth();
        require '../controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->index();
        break;
    case '/logout':
        require '../controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;
    default:
        http_response_code(404);
        echo "404 Not Found";
        break;
}