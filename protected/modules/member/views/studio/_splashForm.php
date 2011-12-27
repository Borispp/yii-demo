<div class="form">
	<?php $form=$this->beginWidget('YsaMemberForm', array(
			'id'=>'studio-splash-form',
			'enableAjaxValidation'=>false,
			'htmlOptions'=>array('enctype' => 'multipart/form-data'),
	)); ?>
	<section>
		<?php echo $form->labelEx($entry,'text'); ?>
		<div>
			<?php echo $form->textArea($entry,'text', array('cols' => 50, 'rows' => 5)); ?>
			<?php echo $form->error($entry,'text'); ?>
		</div>
	</section>
	
	<section class="button">
		<?php echo YsaHtml::submitButton('Save'); ?>
	</section>
	<?php $this->endWidget(); ?>
</div>