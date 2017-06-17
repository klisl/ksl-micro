<?php

namespace App\Models;

use Services\DataBase\Db;
use Services\Model;

class Site extends Model{

	/*
	 * Для работы с базой данных нужно наследовать класс Model
	 * и далее получить свойство db:
	 * $db = $this->db
	 * или напрямую получить объект из контейнера:
	 * $db = app('db');
	 * 
	 */
	public function getPost($id)
	{
		$one = $this->db->getRow("SELECT * FROM posts WHERE id =?i", $id);		
		return $one;
	}
	
	
	public function getAllPosts(){
		$all = $this->db->getAll("SELECT * FROM posts");		
		return $all;
	}
}