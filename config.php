<?php

return [
	'default_controller' => 'site', //контроллер по-умолчанию
	'default_action' => 'index', //действие по-умолчанию
	'default_template' => 'template.php', //шаблон по-умолчанию
	
	'seo_url' => true, // ЧПУ (использование алиасов в URL)
	
	'data_base' => [
		'host'      => 'localhost',
		'user'      => 'root',
		'pass'      => '',
		'db'        => 'test',
		'charset'   => 'utf8',
	],
];