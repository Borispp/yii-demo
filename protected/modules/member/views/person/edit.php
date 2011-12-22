<?php echo YsaHtml::pageHeaderTitle('Edit Shooter'); ?>

<div class="w">
	<?php $this->renderPartial('_form', array(
		'entry' => $entry,
	)); ?>
</div>