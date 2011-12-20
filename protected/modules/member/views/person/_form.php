<div class="form">
	<?php $form=$this->beginWidget('YsaMemberForm', array(
			'id'=>'studio-add-person-form',
			'enableAjaxValidation'=>false,
			'htmlOptions'=>array('enctype' => 'multipart/form-data'),
	)); ?>
	<section>
		<?php echo $form->labelEx($entry,'name'); ?>
		<div>
			<?php echo $form->textField($entry,'name', array('maxlength' => 100)); ?>
			<?php echo $form->error($entry,'name'); ?>
		</div>
	</section>
	
	<section>
		<?php echo $form->labelEx($entry,'photo'); ?>
		<div>
			<?php echo $form->fileField($entry,'photo', array('maxlength' => 100)); ?>
			<?php echo $form->error($entry,'photo'); ?>
		</div>
	</section>

	<section class="button">
		<?php echo YsaHtml::submitButton($entry->isNewRecord ? 'Add' : 'Save'); ?>
	</section>
	<?php $this->endWidget(); ?>
</div>