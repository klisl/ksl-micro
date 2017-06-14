<!DOCTYPE html>
<html>
	<head>
		<title>Заголовок</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href='css/style.css' />
	</head>
	<body>
		
		<header>
			<h2><?= 'шапка шаблона' ?></h2>
		</header>
		
		<div class="content">
			<!--подключение файла-представления-->
			<?php require($fileView);  ?>
		</div>
		
		<div class="widgets">
			<!--вставка виджета-->
			<?php \App\Widgets\StockWidget::widget() ?>
		</div>
		
		<footer>
			<h2><?= 'подвал шаблона' ?></h2>
			<p><a href="http://klisl.com">&copy; KSL </a></p>
		</footer>

	</body> 
</html>