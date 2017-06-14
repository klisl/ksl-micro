<?php

define('ROOT_DIR', dirname(__FILE__)); //путь к корневой папке
define('TEMPLATE_DIR', dirname(__FILE__) . '/app/views'); //путь к папке с шаблоном
define('HOST_DIR', 'http://'.$_SERVER['HTTP_HOST']); //хост
define('ERROR_DEFAULT', 'site/not_found'); //контроллер/действие для ошибки 404


//Включаем вывод ошибок и предупреждений в процессе разработки приложения
ini_set('display_errors', 1);
error_reporting(E_ALL);