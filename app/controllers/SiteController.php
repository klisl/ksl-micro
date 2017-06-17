<?php

namespace App\Controllers;

use Service\MainController;
use App\Models\Site;
use Service\Session;

/*
 * Название класса и файла контроллера должно быть с большой буквы и 
 * заканчиваться на "Controller"
 */
class SiteController extends MainController {

	public function index()
	{
		
		$data = 'Главная страница';

		/*
		 * Работа с моделью. 
		 */			
		$model = new Site();
		$posts = $model->getAllPosts();
		
		//self::$template = 'template2.php'; // пример смены основного шаблона

		
		return $this->render('site/index', [
			'data' => $data,
			'posts' => $posts,
		]);
	}
	
	
	public function post()
	{		
		/* 
		 * получение нужного GET параметра с помощью функции-помошника
		 * аналог $id = $this->request->get['id'];
		 */
		$id = get('id'); 
		
		$model = new Site();
		$post = $model->getPost($id); //Передача GET параметра для получения отдельного поста.
				
		return $this->render('site/post', [
			'post' => $post,
		]);
	}
	
	
	public function contacts()
	{
		return $this->render('site/contacts');
	}
	
	
	public function not_found()
	{
		//создаем заголовки
		app('response')->addHeader("HTTP/1.x 404 Not Found");
		app('response')->addHeader("Status: 404 Not Found");
		return $this->render('site/not_found');
	}
	
}