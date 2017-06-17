<?php

namespace Service;

class Widget {

	public static function widget(){
		static::index();
	}
	
	
	public static function render($route, $data = array()) {

		//Путь к файлу представления виджета
		$fileView = ROOT_DIR . '/app/widgets/views/'. $route .'.php';
		
		
		if (is_file($fileView)) {
			
			extract($data); //извлечение данных из массива

			ob_start(); //включение буферизации

			require($fileView);

			$output = ob_get_clean(); //сохранение вывода в файл
			
		} else throw new Exception("Файл-представления $fileView для виджета не найден.");
		
		
		echo $output;
	}

}
