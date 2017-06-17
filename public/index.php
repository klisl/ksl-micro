<?php 

require_once '../define.php'; //константы	

require_once '../app/helpers.php'; //глобальные функции-помошники	

require_once '../services/ClassLoader.php';	
spl_autoload_register(['ClassLoader', 'autoload'], true, true); //автозагрузчик классов


$app = new Services\Application();
$app->run();