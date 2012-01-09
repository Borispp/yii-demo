<div class="w">
	<h3><?php echo $event->name; ?></h3>

	<?php $this->renderPartial('_form', array(
		'entry' => $entry,
	)); ?>
</div>