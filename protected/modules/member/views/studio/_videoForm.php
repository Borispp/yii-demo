<div class="form standart-form">
	<?php $form=$this->beginWidget('YsaMemberForm', array(
			'id'=>'studio-video-form',
			'action' => array('saveVideo')
	)); ?>
	<section class="cf">
		<?php echo $form->labelEx($entry, 'video'); ?>
		<div>
			<?php echo $form->textField($entry, 'video'); ?>
			<?php echo $form->error($entry, 'video'); ?>
		</div>
	</section>
	<div class="button">
		<?php echo YsaHtml::submitButton('Save', array('class' => 'blue', 'data-loading' => 'Parsing', 'data-value' => 'Save')); ?>
	</div>
	<?php $this->endWidget(); ?>
</div>