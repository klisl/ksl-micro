<?php 

require_once '../define.php'; //константы	

require_once '../app/system/helpers.php'; //глобальные функции-помошники	

require_once '../app/system/ClassLoader.php';	
spl_autoload_register(['ClassLoader', 'autoload'], true, true); //автозагрузчик классов


$app = new Vendor\Application();
$app->run();