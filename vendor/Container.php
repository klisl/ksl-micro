<?php

namespace Vendor;

use ReflectionClass;


class Container
{

	protected static $instance; // объект контейнера 	
 
	protected $bindings = []; //содержимое контейнера - алиас/класс
	protected $instances = []; //содержимое контейнера - созданные объекты

	/*
	 * Возвращает глобально доступный экземпляр контейнера (синглтон).
	 */
	public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }
	
	
    private function __construct()
    {
		//сохраняет в контейнер служебные классы при создании объекта приложения
        $this->setCoreClasses($this->getCoreClasses());		
    }

	
	/*
	 * Возвращает требуемый объект из контейнера разрешая его зависимости
	 */
    protected function getObj($alias, array $arguments=null)
    {
		//Если уже создавался экземпляр нужного класса - возвращаем его из контейнера
		if(isset($this->instances[$alias])){
			return $this->instances[$alias];
		}

		//Проверяет наличие класса по переданному ключу
        if (isset($this->bindings[$alias])) {
			
			$reflector = new ReflectionClass($this->bindings[$alias]);
			
			//Получаем метод конструктора класса
			$constructor = $reflector->getConstructor();
			$re_args = [];
			
			
			if ($constructor){
				//получаем параметры конструктора
				$params = $constructor->getParameters();
				
				foreach ($params as $key => $param) {
									
					/*
					 * У текущего параметра пробуем получить класс
					 * или интерфейс
					 */
					$paramClass = $param->getClass();

					if($paramClass) {
						//Получаем название класса(интерфейса)
						$class = $paramClass->name;
						/*
						 * Проверяем - не интерфейс ли это
						 * если да - ищем реализующий класс в контейнере
						 */
						$argument = $this->class_of_interface($class);

					} else {
						/* 
						 * Иначе параметр не является классом или интерфейсом и
						 * для него не задано значение по-умолчанию.
						 * Если с запросом к контейнеру переданы аргументы - используем
						 */
						if($arguments){
							if(!is_array($arguments)) throw new Exception("Аргументы должны быть переданы в массиве");
							//получаем первый аргумент и удаляем его из массива
							$argument = array_shift($arguments);

						} elseif($param->isDefaultValueAvailable()){
							//Если есть значение по-умолчанию, то оставляем его
							$argument = $param->getDefaultValue();

						} else {
							throw new Exception("Не передан обязательный аргумент {$param} в конструктор класса {$this->bindings[$alias]}");
						}
					}
					//Сохраняем в массив все аргументы конструктора
					$re_args[$param->name] = $argument;
						
				}			
			} 

			/*
			 * РАБОТАЕМ С АРГУМЕНТАМИ
			 */
			if(isset($re_args)){
				//массив зарегистрированных в контейнере классов
				$arrClasses = $this->getCoreClasses();

				/*
				 * Перебираем аргументы конструктора в цикле для 
				 * поиска и внедрения зависимостей
				 * &$arg - передаем по ссылке для замены класса на объект
				 */
				foreach($re_args as $key => &$arg){
				
					/*
					 * Ищем в ЗНАЧЕНИЯХ массива данный параметр(класс)
					 * возвращает его ключ если найден
					 */
					$key = array_search($arg, $arrClasses);
					/*
					 * Создаем объект данного аргумента для внедрения зависимости в конструктор
					 * Используется рекурсия для внедрения зависимостей в создаваемый объект аргумента
					 */
					if($key) $arg = $this->getObj($key) ;

				}
			}
			
			/*
			 * Создание экземпляра класса полученного из контейнера
			 *  на основе Reflection API с указанными аргументами для конструктора
			 */
			$class_instance = $reflector->newInstanceArgs((array)$re_args);
			//сохраняем созданный объект в контейнер
			$this->instances[$alias] = $class_instance;
			
			return $class_instance;
			
        } else 	throw new Exception("Компонент $alias не найден в контейнере");

    }

	
	/*
	 * Проверяет переданный класс - является ли интерфейсом,
	 * если да - возвращает первый реализующий его класс из контейнера
	 */
	protected function class_of_interface($paramClass)
	{
		
		$reflector_int = new ReflectionClass($paramClass);
		//Если зависимость - интерфейс
		if($reflector_int->isInterface()){
			/*
			 * Ищем реализующий его класс в контейнере
			 */
			foreach ($this->bindings as $inst ){

				$ref = new ReflectionClass($inst);

				if($ref->implementsInterface($paramClass)){

					return $inst; //возвращаем класс
					break;
				} 

			}							
		}
		return $paramClass;
	}
	
	
	public function make($alias, $params=null)
	{
		return $this->getObj($alias, $params);
	}
	
	
	//Служебные классы для добавления в контейнер при загрузке приложения
	protected function getCoreClasses()
    {
        return require (ROOT_DIR.'/vendor/aliases.php');
    }
	
	
	//список сохраненных классов
	public function getBindings()
    {
        return $this->bindings;
    }
	//список сохраненных объектов
	public function getInstances()
    {
        return $this->instances;
    }

	
	/*
	 * Сохраняет переданный класс или объект в контейнер
	 * Передается в массиве
	 * Формат: ключ => класс/объект
	 */
	public function setCoreClasses( array $essences)
    {			
		foreach($essences as $key => $essence){

			if(!is_object($essence)){
				$this->bindings[$key] = $essence;
			} else{
				$this->instances[$key] = $essence;
			} 					
		}
    }
	
	
	
	/*
	 * Простое получение объекта из контейнера при вызове алиаса
	 * в виде свойства:
	 * $app->request; 
	 */
	public function __get($alias)
    {
        return $this->getObj($alias);
    }
	
	private function __clone() {} //запрещаем клонирование объекта
		
}