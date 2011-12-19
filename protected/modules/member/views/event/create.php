<?php echo YsaHtml::pageHeaderTitle('Create Event'); ?>


<?php $this->renderPartial('_form', array(
	'entry' => $entry,
)); ?>