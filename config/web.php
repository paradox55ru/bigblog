<?php
// тут другие настройки...
'components' => [
	// Другие компоненты...
    'rabbitMQ' => [
        'class' => 'app\components\RabbitMQ',
		'host' => 'localhost', // Адрес RabbitMQ сервера
        'port' => 5672,        // Порт RabbitMQ
        'user' => 'guest',     // Имя пользователя
        'password' => 'guest',  // Пароль
    ],
    'redis' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '127.0.0.1', // Адрес сервера Redis
        'port' => 6379, // Порт по умолчанию для Redis
        'database' => 0, // Номер базы данных Redis
    ],
    'cache' => [
        'class' => 'yii\redis\Cache', // Используем Redis в качестве компонента кэширования
    ],
	// тут другие настройки...
	'urlManager' => [
		'enablePrettyUrl' => true,
		'showScriptName' => false,
		'rules' => [
			// GET /api/user-statistics?search=Boby&page=1&limit=10&sort=comments_count&order=desc
			'api/user-statistics' => 'user-statistics/index',
		],
	],
],
// тут другие настройки...