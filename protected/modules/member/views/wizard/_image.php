<p>
	<?php echo YsaHtml::image($image['url']); ?>
	<?php echo YsaHtml::link('x', array('application/delete/image/' . $name), array('rel' => $name, 'class' => 'delete')); ?>
</p>