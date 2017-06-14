<?php

namespace Vendor;
use Vendor\Container;

class Application
{
	public function run()
    {
        //Создание объекта сервис-контейнера
		$app = Container::getInstance();

		$app->request; //создание объекта класса Request
			
		/*
		 * Создание объекта для работы с БД.
		 * Используется класс SafeMySQL для безопасной работы
		 */
		$db = new DataBase\SafeMySQL(config('data_base'));
		//сохранение его в контейнер
		$db_obj = $app->setCoreClasses(['db' => $db]);
		
		
		$router = $app->router; //создание объекта класса Router		
		$router->distribute(); //Метод разбивающий входящий запрос на контроллер/действие
	
		
		/*
		 * Метод execute() объекта Action вызывает на исполнение 
		 * контроллер/действие в зависимости от запроса.
		 */
		$result = $app->action->execute();
		
		/*
		 * У объекта Response вызывается метод отправляющий заголовки
		 * и возвращающий результат вывода
		 */
		$output = $app->response->output($result);
		
			
		echo $output;
    }
}
