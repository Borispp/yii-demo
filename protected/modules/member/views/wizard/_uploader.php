<div class="upload">
	<?php if (isset($info)) : ?>
	    <p><?php echo $info; ?></p>
	<?php endif; ?>
	<div id="wzd-<?php echo $name?>-upload-container" class="container">
		<span class="loading">loading...</span>
		<a href="#" id="wzd-<?php echo $name?>-upload-browse" class="btn" rel="<?php echo $name; ?>"><?php echo $model->{$name} ? 'Change' : 'Upload' ?></a>
	</div>
</div>
<div class="value value-image">
	<?php if ($model->{$name}) : ?>
		<figure>
			<?php echo YsaHtml::link(YsaHtml::image($model->{$name}['url']), $model->{$name}['url'], array('class' => 'fancybox', 'rel' => 'external')); ?>
			<?php echo YsaHtml::link('x', array('application/delete/image/' . $name), array('rel' => $name, 'class' => 'delete')); ?>
		</figure>
	<?php endif; ?>
</div>