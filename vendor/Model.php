<?php

namespace Vendor;

use Vendor\DataBase\Db;

abstract class Model {
	public $db;

	public function __construct() {
		//получение и сохранение объекта для работы с БД
		$this->db = app('db');
	}

}