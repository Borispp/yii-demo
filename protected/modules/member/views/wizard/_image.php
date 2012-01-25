<?php if ($image) : ?>
	<figure>
		<?php echo YsaHtml::link(YsaHtml::image($image['url']), $image['url'], array('class' => 'fancybox', 'rel' => 'external')); ?>
		<?php echo YsaHtml::link('x', array('application/delete/image/' . $name), array('rel' => $name, 'class' => 'delete')); ?>
	</figure>
<?php endif; ?>