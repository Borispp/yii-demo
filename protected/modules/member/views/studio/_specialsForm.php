<div class="form">
	<?php $form=$this->beginWidget('YsaMemberForm', array(
			'id'=>'studio-specials-form',
			'enableAjaxValidation'=>false,
			'htmlOptions'=>array('enctype' => 'multipart/form-data'),
	)); ?>
	<section>
		<?php echo $form->labelEx($entry,'specials'); ?>
		<div>
			<?php echo $form->fileField($entry,'specials'); ?>
			<?php echo $form->error($entry,'specials'); ?>
		</div>
	</section>
	
	<section class="button">
		<?php echo YsaHtml::submitButton('Save'); ?>
	</section>
	<?php $this->endWidget(); ?>
</div>