<?php 

require_once '../define.php'; //константы	

require_once '../app/helpers.php'; //глобальные функции-помошники	

require_once '../vendor/ClassLoader.php';	
spl_autoload_register(['ClassLoader', 'autoload'], true, true); //автозагрузчик классов


$app = new Vendor\Application();
$app->run();