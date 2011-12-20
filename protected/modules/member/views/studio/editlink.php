<?php echo YsaHtml::pageHeaderTitle('Edit Studio Link'); ?>

<?php $this->renderPartial('_editlinkForm', array(
	'entry' => $entryLink,
)); ?>