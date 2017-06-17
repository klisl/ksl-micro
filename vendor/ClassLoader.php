<?php 
/*
 * Для использования автозагрузчика необходимо следовать рекомедациям PSR-4
 * или придется внести изменения в метод library() для формирования пути к файлу классов.
 * Так же можно вписать в карту классов (classes.php) произвольный путь к файлу.
 */
class ClassLoader {
 
    public static $classMap;
    public static $addMap = array();
	/*
	 * Где искать указанное пространство имен, если его название не совпадает с названием каталога
	 * Например пространство имен Service находится в папке vendor
	 */
    public static $psr = [
		'Service' => 'vendor'
	];
 
    public static function autoload($className)
	{		
		//подключаем и сохраняем карту классов. Добавляем пользовательские классы.
		self::$classMap = array_merge(require('classes.php'), self::$addMap);

	   //Ищем в карте классов
		if (isset(self::$classMap[$className])) {
			$filename = self::$classMap[$className];
			include_once ROOT_DIR . $filename;

		//Ищем в папках
		} else {
			self::library($className);
		}

		//Проверка был ли объявлен класс
		if (!class_exists($className, false) && !interface_exists($className, false) && !trait_exists($className, false)) {
			throw new Exception('Невозможно найти класс '.$className);
		}   
    }
     
    public static function library($className)
	{

		$arr_className = explode('\\', $className); //  разделяем строку по символу "\"
		
		/*
		 * Название первого элемента пространства имен класса может отличаться от
		 * названия каталога (vendor), поэтому проверяем на что его нужно заменить
		 */
		Foreach (self::$psr as $key => $elem){
			if($arr_className[0] == $key){
				$arr_className[0] = $elem;				
			}
		}
		$className = implode("\\", $arr_className);
		
		
		$filename = ROOT_DIR . '/'. $className . ".php"; //формирование названия файла с классом
		
			if (is_readable($filename)) {
				require_once $filename;
			}
	}
     
}
