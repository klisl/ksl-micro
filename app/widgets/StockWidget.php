<?php

namespace App\Widgets;

use Services\Widget;


class StockWidget extends Widget{
	
	public static function index()
	{
		$text = 'Контент виджета.';
		
		self::render('stock',[
            'text' => $text,			
        ]);
	}
}