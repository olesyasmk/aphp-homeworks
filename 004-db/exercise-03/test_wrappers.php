<?php

require_once __DIR__ . '/autoload.php';

use App\Classes\Shop;
use App\Classes\Client;
use App\Classes\Order;

try {
	$dbFile = __DIR__ . '/shop_database.db';
	
	$pdo    = new PDO( "sqlite:" . $dbFile );
	$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$pdo->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );
	$pdo->exec( "PRAGMA foreign_keys = ON;" );
	
	echo "--- Тестирование DatabaseWrapper ---\n\n";
	
	$shopModel = new Shop( $pdo );
	echo "Добавляем новый магазин...\n";
	$newShop = $shopModel->insert( [ 'name', 'address' ], [ 'ЭлектроМир', 'ул. Соборная, 22' ] );
	print_r( $newShop );
	
	echo "\nОбновляем адрес магазина с ID {$newShop['id']}...\n";
	$updatedShop = $shopModel->update( $newShop[ 'id' ], [ 'address' => 'ул. Центральная, 1' ] );
	print_r( $updatedShop );
	
	$clientModel = new Client( $pdo );
	$clientId    = 1;
	echo "\nИщем клиента с ID $clientId...\n";
	$client = $clientModel->find( $clientId );
	print_r( $client );
	
	$orderModel = new Order( $pdo );
	echo "\nУдаляем заказ с ID 5...\n";
	$success = $orderModel->delete( 5 );
	echo $success ? "Заказ удален успешно.\n" : "Ошибка при удалении заказа.\n";
	
	$order = $orderModel->find( 5 );
	echo empty( $order ) ? "Заказ №5 не найден (как и ожидалось).\n" : "Ошибка: заказ №5 все еще существует.\n";
	
}
catch ( PDOException $e ) {
	die( "Ошибка: " . $e->getMessage() );
}
