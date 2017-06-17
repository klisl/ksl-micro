<?php
/*
 * Служебные классы для добавления в сервис-контейнер при начальной загрузке приложения
 */
return [

	'request' => \Services\Request::class,
	'router' => \Services\Router::class,
	'response' => \Services\Response::class,
	'action' => \Services\Action::class,
	
];