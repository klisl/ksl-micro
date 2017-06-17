<?php 
/*
 * Для использования автозагрузчика необходимо следовать рекомедациям PSR-4
 * или придется внести изменения в метод library() для формирования пути к файлу классов.
 * Так же можно вписать в карту классов (classes.php) произвольный путь к файлу.
 */
class ClassLoader 
{
 
    public static $classMap;
    public static $addMap = array();
	/*
	 * Где искать пространство имен класса.
	 * Например пространство имен Services находится в папке lib/classes
	 */
    public static $psr = [
//		'Services' => 'lib/classes'
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
		 * Последний элемент массива - это название класса (оно же и название файла)
		 * сохраняем его и убираем из массива
		 */
		$class = array_pop($arr_className);
		
		/*
		 * Название первого элемента пространства имен класса может отличаться от
		 * названия каталога (services), поэтому проверяем на что его нужно заменить
		 */
		Foreach (self::$psr as $key => $elem){
			if($arr_className[0] == $key){
				$arr_className[0] = $elem;				
			}
		}
		/*
		 * Соединяем массив обратно в строку. По сути это является пространством имен данного класса
		 * оно же является и путем к файлу класса
		 */
		$classNameSpace = implode(DIRECTORY_SEPARATOR, $arr_className);
		
		/*  
		 * Формирование названия файла с классом.
		 * Т.к. названия папок везде с маленькой буквы, а пространства имен могут быть с большой - 
		 *  при вставке $classNameSpace переводим в нижний регистр
		 */
		$filename = ROOT_DIR . DIRECTORY_SEPARATOR . strtolower($classNameSpace) . DIRECTORY_SEPARATOR . $class . ".php";

			if (is_readable($filename)) {
				require_once $filename;
			}
	}
     
}
