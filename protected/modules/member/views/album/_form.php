<div class="form">
<?php $form=$this->beginWidget('YsaMemberForm', array(
		'id'=>'edit-album-form',
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
	<?php echo $form->labelEx($entry,'shooting_date'); ?>
	<div>
		<?php echo $form->textField($entry,'shooting_date', array('maxlength' => 100)); ?>
		<?php echo $form->error($entry,'shooting_date'); ?>
	</div>
</section>

<section>
	<?php echo $form->labelEx($entry, 'place'); ?>
	<div>
		<?php echo $form->textField($entry,'place', array('maxlength' => 100)); ?>
		<?php echo $form->error($entry,'place'); ?>
	</div>
</section>

<section>
	<?php echo $form->labelEx($entry,'description'); ?>
	<div>
		<?php echo $form->textArea($entry,'description', array('cols' => 40, 'rows' => 4)); ?>
		<?php echo $form->error($entry,'description'); ?>
	</div>
</section>

<section>
	<?php echo $form->labelEx($entry,'state'); ?>
	<div>
		<?php echo $form->dropDownList($entry, 'state', $entry->getStates()); ?>
		<?php echo $form->error($entry,'state'); ?>
	</div>
</section>
	
<section>
	<?php echo $form->labelEx($entry,'can_share'); ?>
	<?php echo $form->checkBox($entry, 'can_share', array('checked' => $entry->can_share)); ?>
	<div>
		<?php echo $form->error($entry,'can_share'); ?>
	</div>
</section>
	
<section>
	<?php echo $form->labelEx($entry,'can_order'); ?>
	<?php echo $form->checkBox($entry, 'can_order', array('checked' => $entry->can_share)); ?>
	<div>
		<?php echo $form->error($entry,'can_order'); ?>
	</div>
</section>

<section class="button">
	<?php echo YsaHtml::submitButton($entry->isNewRecord ? 'Create' : 'Save'); ?>
</section>

<?php $this->endWidget(); ?>
</div>