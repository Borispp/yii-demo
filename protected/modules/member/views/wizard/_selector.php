<div class="description">
	<?php if ($info) : ?>
		<p><?php echo $info; ?></p>
	<?php endif; ?>
</div>
<div class="main-container">
	<ul class="switcher">
		<?php echo $form->radioButtonList($model, $radioName, $radioValues, array('separator' => '', 'template' => '<li>{input} {label}</li>'));?>
	</ul>
	<div class="color">
		<div class="value">
			<div class="color-selector">
				<?php echo $form->textField($model, $colorName, array('class' => 'colors')); ?>
				<?php echo $form->error($model, $colorName); ?>
			</div>
		</div>
	</div>
	<div class="upload">
		<div id="wzd-<?php echo $imageName?>-upload-container" class="container">
			<span class="loading">loading...</span>
			<a href="#" id="wzd-<?php echo $imageName?>-upload-browse" class="btn" rel="<?php echo $imageName; ?>"><?php echo $model->{$imageName} ? 'Change' : 'Upload' ?></a>
		</div>
	</div>
</div>
<div class="value value-image">
	<?php if ($model->{$imageName}) : ?>
		<figure>
			<?php echo YsaHtml::link(YsaHtml::image($model->{$imageName}['url']), $model->{$imageName}['url'], array('class' => 'fancybox', 'rel' => 'external')); ?>
			<?php echo YsaHtml::link('x', array('application/delete/image/' . $imageName), array('rel' => $imageName, 'class' => 'delete')); ?>
		</figure>
	<?php endif; ?>
</div>