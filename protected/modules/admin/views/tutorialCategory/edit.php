<div class="g12">
	
	<?php echo $this->renderPartial('/_messages/save');?>
	
	<div class="form">
		<?php $this->renderPartial('_form', array(
			'entry' => $entry,
		))?>
	</div>
</div>