<?php 

//для отладки - вывод содержимого переменной на экран в удобном виде
function debug($arr, $exit = true)
{
	echo '<pre>' . print_r($arr, true) . '</pre>';
	if($exit) exit;
}


/*
 * Получение доступа к объекту контейнера
 * $bindings - алиас привязанного класса или объекта
 * $parameters - параметры, которые можно передать (в массиве)
 *	конструктору привязанного класса при создании объекта
 */
function app($bindings=null, array $parameters = [])
{
	// вызов без параметров вернет объект всего контейнера
	if (is_null($bindings)) {
		return Service\Container::getInstance();
	}
	
	return Service\Container::getInstance()->make($bindings, $parameters);
}


//получает значение массива конфигурации (настройки приложения) по ключу
function config($key)
{
	$arr = require ROOT_DIR.'/config.php';
	return $arr[$key];
}


/*
 * Возвращает объект GET-запроса если запросить без параметров
 * или значение определенного параметра
 */
function get($key = null)
{	
	if (is_null($key)) {
		return app('request')->get;
	}
	if(isset(app('request')->get[$key])) {
		return app('request')->get[$key];
	} else return null;	
}


/*
 * Возвращает объект POST-запроса если запросить без параметров
 * или значение определенного параметра
 */
function post($key = null)
{
	if (is_null($key)) {
		return app('request')->post;
	}
	if(isset(app('request')->post[$key])) {
		return app('request')->post[$key];
	} else return null;	
}


/*
 * Формирование ссылок.
 * url(контроллер/действие, array(параметры));
 * При использовании ЧПУ ссылок, в массиве передавать только параметры укзанные в 
 * базе данных в ячейке query в таблице url_alias.
 * Пример:
 * <a href="<?=url('site/post', ['id'=>1])?>">Ссылка</a>
 */
function url($url, array $arguments = null){
	$str = '';
	
	if($arguments){		
		foreach($arguments as $key => $argument){
			$str = $str."&$key=$argument";
		}
	}
	
	if(config('seo_url')){
		$url = $url.$str;
		$db = app('db');		
		$result = $db->getOne("SELECT keyword FROM url_alias WHERE query=?s", $url);
		if($result) return $result;
	}
		return HOST_DIR . '/index.php?route=' . $url . $str;
	
}
