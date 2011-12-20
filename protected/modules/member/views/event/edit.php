<?php echo YsaHtml::pageHeaderTitle('Edit Event'); ?>


<?php $this->renderPartial('_form', array(
	'entry' => $entry,
)); ?>