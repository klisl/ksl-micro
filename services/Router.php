<?php

namespace Services;

class Router {
	
	protected $request;
	protected $route;

	
	//Внедрение зависимости в конструктор (класс Request)
	public function __construct(Request $request) 
	{
		$this->request = $request;
		$this->getRoute();
	}
		
	
	public function getRoute() 
	{
		
		$request = $this->request;

		//Если используется SEO_URL
		if(config('seo_url') && isset($this->request->get['_route_'])){
			
			$route = $this->request->get['_route_'];
			unset($this->request->get['_route_']);
												
			//Ищем алиас в БД
			$db = app('db');
			$result = $db->getOne("SELECT query FROM url_alias WHERE keyword=?s", $route);
			//Если не найден алиас
			if ($result == '') $result = ERROR_DEFAULT;
			
			
			/* Разделитель GET-параметров в таблице url_alias должен быть "&"
			 * Например из БД получили строку site/index&id=1
			 * разбиваем на массив по разделителю
			 * отделяем GET параметры, которые идут после символа &
			 */
			$parts = explode('&', $result);
			$route = $parts[0]; //контроллер/действие
			unset($parts[0]);
			
			/*
			 * Get параметры сохраняем в объект Request
			 * Например id = 1
			 */
			foreach($parts as $part){
				$ar = explode('=', $part);
				$this->request->get[$ar[0]] = $ar[1]; 				
			}

			
		/*
		 * Если не используется SEO_URL и передаются параметры в route, например
		 * http://test.loc/index.php?route=site/post&id=1
		 */			
		} else if (!config('seo_url') && isset($request->get['route'])) {
			$route = $request->get['route'];
			
		//Если нет GET параметров - это главная страница
		} else if (!$request->get){
			
			//Убираем дубль главной страницы с "index.php"
			if (preg_match("/^\/index.php/", $_SERVER['REQUEST_URI'] ) ) {
				app('response')->redirect(HOST_DIR);
			}
			
			//контроллер и действие по-умолчанию
			$route = config('default_controller') .'/'. config('default_action');

		} else $route = ERROR_DEFAULT;
		
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);

		$this->route = $route;
		
	}	
		
		
	public function distribute() 
	{	
		
		$route = ucfirst($this->route); //первую букву названия контроллера делаем заглавной
		/*
		 * Разбиваем GET запрос на массив по разделителю "/", например:
		 * Array
		 * (
		 *	[0] => site
		 *	[1] => index
		 * )
		 * и сохраняет в свойства
		 */		
		$parts = explode('/', preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route));

		// Разделяем маршрут на контроллер и действие
		while ($parts) {
			$file = ROOT_DIR . '/app/controllers/' . implode('/', $parts) . 'Controller.php';

			/*
			 * Если файл найден, значит сохраняем текущие значения для контроллера и действия
			 */
			if (is_file($file)) {
				$route = implode('/', $parts);		
				break;
			} else {
				$method = array_pop($parts); //извлекает и возвращает последнее значение
			}
		}
				
		/*
		 * Получение объекта класса Action, разбивающего текущий запрос на части
		 * контроллер/действие и вызывающий нужный метод контроллера.
		 */
		$action = app('action', [$route, $method]);		
		
	}
}