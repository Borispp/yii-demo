<div class="form <?php echo Yii::app()->request->isAjaxRequest ? 'ajax-form' : 'standart-form'?>">
	<?php $form=$this->beginWidget('YsaMemberForm', array(
			'id'=>'studio-add-person-form',
			'enableAjaxValidation'=>false,
			'htmlOptions'=>array('enctype' => 'multipart/form-data'),
	)); ?>
	<section class="cf">
		<?php echo $form->labelEx($entry,'name'); ?>
		<div>
			<?php echo $form->textField($entry,'name', array('maxlength' => 100)); ?>
			<?php echo $form->error($entry,'name'); ?>
		</div>
	</section>
	
	
	<?php if ($entry->photo) : ?>
		<section class="cf">
			<?php echo $entry->photo(); ?>
				<?php echo YsaHtml::link('x', array('person/deleteImage/' . $entry->id)); ?>
		</section>
	<?php else: ?>
		<section class="cf">
			<?php echo $form->labelEx($entry,'photo'); ?>
			<div>
				<?php echo $form->fileField($entry,'photo', array('maxlength' => 100)); ?>
				<?php echo $form->error($entry,'photo'); ?>
			</div>
		</section>
	<?php endif; ?>
	

	
	<section class="cf">
		<?php echo $form->labelEx($entry,'description'); ?>
		<div>
			<?php echo $form->textArea($entry,'description', array('cols' => 40, 'rows' => 4)); ?>
			<?php echo $form->error($entry,'description'); ?>
		</div>
	</section>

	<section class="button">
		<?php echo YsaHtml::submitButton($entry->isNewRecord ? 'Add' : 'Save', array('class' => 'blue')); ?>
	</section>
	<?php $this->endWidget(); ?>
</div>