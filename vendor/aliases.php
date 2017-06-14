<?php
/*
 * Служебные классы для добавления в сервис-контейнер при начальной загрузке приложения
 */
return [

	'request' => \Vendor\Request::class,
	'router' => \Vendor\Router::class,
	'response' => \Vendor\Response::class,
	'action' => \Vendor\Action::class,
	
];