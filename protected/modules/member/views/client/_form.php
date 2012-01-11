<div class="form">
	<?php $form=$this->beginWidget('YsaMemberForm', array(
			'id'=>'client-form',
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
		<?php echo $form->labelEx($entry,'email'); ?>
		<div>
			<?php echo $form->textField($entry,'email', array('maxlength' => 100)); ?>
			<?php echo $form->error($entry,'email'); ?>
		</div>
	</section>

	<section>
		<?php echo $form->labelEx($entry,'password'); ?>
		<div>
			<?php echo $form->textField($entry,'password', array('maxlength' => 100)); ?>
			<?php echo $form->error($entry,'password'); ?>
		</div>
	</section>

	<section>
		<?php echo $form->labelEx($entry,'phone'); ?>
		<div>
			<?php echo $form->textField($entry,'phone', array('maxlength' => 100)); ?>
			<?php echo $form->error($entry,'phone'); ?>
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
		<?php echo $form->labelEx($entry,'description'); ?>
		<div>
			<?php echo $form->textArea($entry,'description', array('cols' => 40, 'rows' => 4)); ?>
			<?php echo $form->error($entry,'description'); ?>
		</div>
	</section>

	<section class="button">
		<?php echo YsaHtml::submitButton($entry->isNewRecord ? 'Add' : 'Save'); ?>
	</section>
	<?php $this->endWidget(); ?>
</div>