<?php
/*
 * Служебные классы для добавления в сервис-контейнер при начальной загрузке приложения
 */
return [

	'request' => \Service\Request::class,
	'router' => \Service\Router::class,
	'response' => \Service\Response::class,
	'action' => \Service\Action::class,
	
];