<?php

namespace Vendor;

/*
 * start - открытие сессии;
 * set - устанавливает значение переменной сессии. $key - строковый ключ, $value - значение;
 * get - получает значение переменной сессии. $key - строковый ключ;
 * unseted - сбрасывает значения всех переменных сессии;
 * destroy - закрытие сессии
 * 
 * Пример использования:
 * 	$session = \Vendor\Session::class;
 *	$session::start();
 *	$session::set('ksl', 10)
 */
class Session {

    private static $_is_started = FALSE;

    public static function start() {
        if (self::$_is_started == FALSE) {
            session_start();
            self::$_is_started = TRUE;
        }
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key) {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        } else {
            return NULL;
        }
    }

    public static function unseted() {
        if (self::$_is_started == TRUE) {
            session_unset();
        }
    }

    public static function destroy() {
        if (self::$_is_started == TRUE) {
            session_unset();
            session_destroy();
        }
    }

}