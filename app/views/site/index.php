
<h3>	
	<?=$data ?>	
</h3>

<?php Foreach($posts as $post): ?>

	<h4>
		<?=$post['title']?>
	</h4>

	<p>
		<?=$post['text']?>
	</p>

<?php endforeach; ?>

	
	<a href="<?=url('site/post', ['id'=>1])?>">Ссылка на пост</a>