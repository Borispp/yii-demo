<div class="form">
	<?php $form=$this->beginWidget('YsaMemberForm', array(
			'id'=>'studio-add-link-form',
			'enableAjaxValidation'=>false,
	)); ?>
	<section>
		<?php echo $form->labelEx($entry,'name'); ?>
		<div>
			<?php echo $form->textField($entry,'name', array('maxlength' => 100)); ?>
			<?php echo $form->error($entry,'name'); ?>
		</div>
	</section>
	<section>
		<?php echo $form->labelEx($entry,'url'); ?>
		<div>
			<?php echo $form->textField($entry,'url', array('maxlength' => 100)); ?>
			<?php echo $form->error($entry,'url'); ?>
		</div>
	</section>
	<section class="button">
		<?php echo YsaHtml::submitButton($entry->isNewRecord ? 'Add' : 'Save'); ?>
	</section>
	<?php $this->endWidget(); ?>
</div>