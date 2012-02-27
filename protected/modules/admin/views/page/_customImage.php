<?php if ($image) : ?>
	<?php echo YsaHtml::link(YsaHtml::image($image['url'] . '?' . time(), ''), $image['url'], array('target' => '_blank')); ?>
	<?php echo YsaHtml::link('Delete', '#', array('class' => 'delete-image', 'data-id' => $field->id)); ?>
<?php endif; ?>