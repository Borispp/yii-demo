<div class="w">
	<?php echo YsaHtml::pageHeaderTitle('Create Album'); ?>

	<h3><?php echo $event->name; ?></h3>

	<?php $this->renderPartial('_form', array(
		'entry' => $entry,
	)); ?>
</div>