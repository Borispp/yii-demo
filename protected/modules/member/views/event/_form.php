<div class="form">
	<?php $form=$this->beginWidget('YsaMemberForm', array(
			'id'=>'create-event-form',
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
		<?php echo $form->labelEx($entry,'date'); ?>
		<div>
			<?php echo $form->textField($entry,'date', array('maxlength' => 100, 'class' => 'date', 'readonly' => true)); ?>
			<?php echo $form->error($entry,'date'); ?>
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

	
	<?php if ($entry->isNewRecord) : ?>
		<section>
			<?php echo $form->labelEx($entry,'type'); ?>
			<div>
				<?php echo $form->dropDownList($entry, 'type', $entry->getTypes()); ?>
				<?php echo $form->error($entry,'type'); ?>
			</div>
		</section>
	<?php else: ?>
		<section>
			<label>Type</label>
			<div>
				<?php echo $entry->type(); ?>
			</div>
		</section>
	<?php endif; ?>
	
	<?php if ($entry->isNewRecord || !$entry->isPortfolio()) : ?>
		<section>
			<?php echo $form->labelEx($entry,'passwd'); ?>
			<div>
				<?php echo $form->textField($entry,'passwd', array('maxlength' => 20)); ?>
				<?php echo $form->error($entry,'passwd'); ?>
			</div>
		</section>
	<?php endif; ?>
	
	<section class="button">
		<?php echo YsaHtml::submitButton($entry->isNewRecord ? 'Create' : 'Save'); ?>
	</section>

	<?php $this->endWidget(); ?>
</div>