<div class="upload">
	<?php if (isset($info)) : ?>
		<p><?php echo $info; ?></p>
	<?php endif; ?>
</div> 
<div class="value value-image">
	<?php if ($model->{$name}) : ?>
		<figure>
			<?php echo YsaHtml::link(YsaHtml::image($model->{$name}['url']), $model->{$name}['url'], array('class' => 'fancybox', 'rel' => 'external')); ?>
		</figure>
	<?php endif; ?>
</div>