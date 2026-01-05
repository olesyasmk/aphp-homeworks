<?php

try {
  $dbFile = __DIR__ . '/shop_database.db';

  $isNew = ! file_exists( $dbFile );

  $pdo = new PDO( "sqlite:" . $dbFile );
  $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
  $pdo->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );

  $pdo->exec( "PRAGMA foreign_keys = ON;" );

  echo "Подключено к базе данных: $dbFile\n";

  $schema = "
    -- Таблица магазинов
    CREATE TABLE IF NOT EXISTS shop (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        address TEXT NOT NULL
    );

    -- Таблица клиентов
    CREATE TABLE IF NOT EXISTS client (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        phone TEXT NOT NULL,
        name TEXT NOT NULL
    );

    -- Таблица продуктов
    CREATE TABLE IF NOT EXISTS product (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        shop_id INTEGER NOT NULL,
        name TEXT NOT NULL,
        price REAL NOT NULL,
        count INTEGER NOT NULL,
        FOREIGN KEY (shop_id) REFERENCES shop(id) ON DELETE CASCADE
    );

    -- Таблица заказов
    CREATE TABLE IF NOT EXISTS `order` (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        shop_id INTEGER NOT NULL,
        client_id INTEGER NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (shop_id) REFERENCES shop(id) ON DELETE CASCADE,
        FOREIGN KEY (client_id) REFERENCES client(id) ON DELETE CASCADE
    );

    -- Таблица соответствий продуктов и заказов
    CREATE TABLE IF NOT EXISTS order_product (
        order_id INTEGER NOT NULL,
        product_id INTEGER NOT NULL,
        price REAL NOT NULL, -- Цена на момент заказа
        PRIMARY KEY (order_id, product_id),
        FOREIGN KEY (order_id) REFERENCES `order`(id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE
    );
    ";

  $pdo->exec( $schema );
  echo "Таблицы успешно созданы.\n";

  $pdo->exec( "DELETE FROM order_product; DELETE FROM `order`; DELETE FROM product; DELETE FROM client; DELETE FROM shop;" );
  $pdo->exec( "DELETE FROM sqlite_sequence WHERE name IN ('shop', 'client', 'product', 'order');" );

  $shops = [
    [ 'Техномиров', 'ул. Кремлевская, 1' ],
    [ 'Вкусная Лавка', 'пр. Ленина, 15' ],
    [ 'Модный Квартал', 'ул. Пушкина, 10' ],
    [ 'Книжный Уголок', 'пер. Литературный, 5' ],
    [ 'Игротека', 'ул. Гагарина, 42' ]
  ];

  $stmtShop = $pdo->prepare( "INSERT INTO shop (name, address) VALUES (?, ?)" );
  foreach ( $shops as $shop ) {
    $stmtShop->execute( $shop );
  }
  echo "Магазины добавлены.\n";

  $clients = [
    [ '+79001112233', 'Иван Иванов' ],
    [ '+79002223344', 'Мария Петрова' ],
    [ '+79003334455', 'Александр Сидоров' ],
    [ '+79004445566', 'Елена Кузнецова' ],
    [ '+79005556677', 'Дмитрий Федоров' ]
  ];

  $stmtClient = $pdo->prepare( "INSERT INTO client (phone, name) VALUES (?, ?)" );
  foreach ( $clients as $client ) {
    $stmtClient->execute( $client );
  }
  echo "Клиенты добавлены.\n";

  $products = [
    [ 1, 'Ноутбук', 85000.00, 10 ],
    [ 1, 'Мышь беспроводная', 1500.00, 50 ],
    [ 2, 'Яблоки (кг)', 120.00, 100 ],
    [ 2, 'Бананы (кг)', 90.00, 200 ],
    [ 3, 'Футболка хлопок', 1200.00, 30 ],
    [ 4, 'Роман "Война и мир"', 850.00, 15 ],
    [ 5, 'Геймпад', 4500.00, 20 ]
  ];

  $stmtProd = $pdo->prepare( "INSERT INTO product (shop_id, name, price, count) VALUES (?, ?, ?, ?)" );
  foreach ( $products as $prod ) {
    $stmtProd->execute( $prod );
  }
  echo "Товары добавлены.\n";

  $orders = [
    [ 1, 1 ],
    [ 1, 2 ],
    [ 2, 3 ],
    [ 3, 4 ],
    [ 5, 5 ]
  ];

  $stmtOrder = $pdo->prepare( "INSERT INTO `order` (shop_id, client_id) VALUES (?, ?)" );
  foreach ( $orders as $order ) {
    $stmtOrder->execute( $order );
  }
  echo "Заказы добавлены.\n";

  $orderProducts = [
    [ 1, 1, 85000.00 ],
    [ 1, 2, 1500.00 ],
    [ 2, 2, 1500.00 ],
    [ 3, 3, 120.00 ],
    [ 4, 5, 1200.00 ],
    [ 5, 7, 4500.00 ]
  ];

  $stmtOP = $pdo->prepare( "INSERT INTO order_product (order_id, product_id, price) VALUES (?, ?, ?)" );
  foreach ( $orderProducts as $op ) {
    $stmtOP->execute( $op );
  }
  echo "Связи товаров и заказов добавлены.\n";

  echo "\n--- Проверка: Таблица магазинов ---\n";
  foreach ( $pdo->query( "SELECT * FROM shop LIMIT 5" ) as $row ) {
    printf( "[%d] %s по адресу %s\n", $row[ 'id' ], $row[ 'name' ], $row[ 'address' ] );
  }

  echo "\n--- Проверка: Таблица товаров ---\n";
  foreach ( $pdo->query( "SELECT p.*, s.name as shop_name FROM product p JOIN shop s ON p.shop_id = s.id LIMIT 5" ) as $row ) {
    printf( "[%d] %s (%.2f руб.) в магазине %s\n", $row[ 'id' ], $row[ 'name' ], $row[ 'price' ], $row[ 'shop_name' ] );
  }

  echo "\n--- Проверка: Заказы ---\n";
  $q = "SELECT o.id, o.created_at, c.name as client_name, s.name as shop_name
          FROM `order` o 
          JOIN client c ON o.client_id = c.id 
          JOIN shop s ON o.shop_id = s.id 
          LIMIT 5";

  foreach ( $pdo->query( $q ) as $row ) {
    printf( "Заказ №%d от %s: %s купил в %s\n", $row[ 'id' ], $row[ 'created_at' ], $row[ 'client_name' ], $row[ 'shop_name' ] );
  }

}
catch ( PDOException $e ) {
  die( "Ошибка базы данных: " . $e->getMessage() );
}
