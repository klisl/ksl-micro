<?php

namespace App\Widgets;

use Service\Widget;


class StockWidget extends Widget{
	
	public static function index()
	{
		$text = 'Контент виджета.';
		
		self::render('stock',[
            'text' => $text,			
        ]);
	}
}