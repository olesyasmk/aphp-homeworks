<?php

require_once __DIR__ . '/autoload.php';

use App\Classes\AuthService;

$authService = new AuthService();

echo "--- Тест 1: Валидный пользователь приложения ---\n";
$authService->checkUser( 'admin', 'password123' );

echo "\n--- Тест 2: Валидный мобильный пользователь ---\n";
$authService->checkUser( 'mobile_user', 'mobile_pass' );

echo "\n--- Тест 3: Неверные данные ---\n";
$authService->checkUser( 'wrong_user', 'wrong_pass' );
