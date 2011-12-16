<?php echo YsaHtml::pageHeaderTitle('Edit Album'); ?>

<h3><?php echo $entry->event()->name; ?></h3>

<?php $this->renderPartial('_form', array(
	'entry' => $entry,
)); ?>
</div>