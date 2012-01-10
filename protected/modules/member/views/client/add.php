<?php echo YsaHtml::pageHeaderTitle('Add Client'); ?>

<div class="w">
	<?php $this->renderPartial('_form', array(
		'entry' => $entry,
	)); ?>
</div>