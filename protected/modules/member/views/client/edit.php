<?php echo YsaHtml::pageHeaderTitle('Edit Client'); ?>
<div class="w">
	<?php $this->renderPartial('_form', array(
		'entry' => $entry,
	)); ?>
</div>