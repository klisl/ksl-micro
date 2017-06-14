<?php

namespace Vendor;

use Vendor\View;
use Vendor\Request;

abstract class MainController {
	protected $request;
	public static $template; //шаблон

	/*
	 * Всем контроллерам даем доступ к объекту Request
	 */
	public function __construct(Request $request) {
		$this->request = $request;
	}
	
	
	public function render($route, $data = array()) {
		$view = new View($route, $data);
		app()->setCoreClasses(['view' => $view]); //добавляем объект в контейнер 
		
		return $view->index();
	}

}