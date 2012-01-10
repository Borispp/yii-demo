<?php echo YsaHtml::pageHeaderTitle('Add Shooter'); ?>

<div class="w">
	<?php $this->renderPartial('_form', array(
		'entry' => $entry,
	)); ?>
</div>