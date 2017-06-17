<?php

namespace Services;

class View {

	protected $route;
	protected $data;
	
	public function __construct($route, $data = []) 
	{
		$this->route = $route;
		$this->date = $data;
	}
	
	public function index() {
		$output = null;
		$route = $this->route;
		$data = $this->date;

		
		$fileView = TEMPLATE_DIR . '/'. $route .'.php'; //Путь к файлу представления

		/*
		 * Если шаблон установлен в контроллере, то берется из него иначе из настроек по-умолчанию
		 */
		$template = MainController::$template ? MainController::$template : config('default_template');
				
		$fileTemplate = TEMPLATE_DIR . '/' . $template; //Путь к шаблону
		
		
		if (is_file($fileView) && is_file($fileTemplate)) {
			
			extract($data); //извлечение данных из массива

			ob_start(); //включение буферизации

			//подключение шаблона
			require($fileTemplate);

			$output = ob_get_clean(); //сохранение вывода в файл
			
		} else throw new Exception("Файл-представления $fileView не найден.");		
		 
		return $output; //возвращаем готовую страницу
	}

}
