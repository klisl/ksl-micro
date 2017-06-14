<?php

namespace Vendor;

use ReflectionClass;

class Action {

	private $route; //название контроллера без окончания "Controller"
	private $method = 'index'; //действие (метод) контроллера

	
	public function __construct($route, $method) 
	{	
		$this->route = $route;
		$this->method = $method;
	}
	

	/*
	 * Метод формирует полный путь к файлу контроллера, подключает его файл,
	 * создает экземпляр класса контроллера и вызывает нужное действие с передачей аргументов
	 */
	public function execute( array $args = array()) 
	{

		$file = ROOT_DIR . '/app/controllers/' . $this->route . 'Controller.php';		

		$class =  '\App\Controllers\\' .$this->route . 'Controller'; //класс контроллера с пространством имен

		if (is_file($file)) {
			include_once($file);		

			//добавляем в контейнер запрашиваемый контроллер
			app()->setCoreClasses([$this->route => $class]);
			
			//получаем объект запрашиваемого контроллера из контейнера
			$controller = app($this->route);
			
		} else {			
			return new Exception('Error: Could not call ' . $this->route . '/' . $this->method . '!');
		}
		
		$reflection = new ReflectionClass($class);
		
		if ($reflection->hasMethod($this->method) && $reflection->getMethod($this->method)->getNumberOfRequiredParameters() <= count($args)) {
			$result = call_user_func_array(array($controller, $this->method), $args);			
			return $result;
			
		} else {
			return new Exception('Error: Could not call ' . $this->route . '/' . $this->method . '!');
		}
		
	}
}
