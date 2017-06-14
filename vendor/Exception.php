<?php

namespace Vendor;

class Exception extends \Exception {

    /*
	 * Переопределим строковое представление объекта 
	 * для создания переносов при выводе исключений.
	 */
    public function __toString() {
		echo '<pre>';
        return parent::__toString();
				
    }
}
