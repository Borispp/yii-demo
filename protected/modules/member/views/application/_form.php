<div class="form standart-form">
	<?php $form=$this->beginWidget('YsaMemberForm', array(
			'id'=>'create-app-form',
	)); ?>
	<section class="cf">
		<?php echo $form->labelEx($entry,'name'); ?>
		<div>
			<?php echo $form->textField($entry,'name', array('maxlength' => 100)); ?>
			<?php echo $form->error($entry,'name'); ?>
			<p class="hint">Name of  your application in the App Store (maximum characters: 100).</p>
		</div>
	</section>
	<section class="cf">
		<?php echo $form->labelEx($entry,'info'); ?>
		<div>
			<?php echo $form->textArea($entry,'info', array('cols' => 40, 'rows' => 8)); ?>
			<?php echo $form->error($entry,'info'); ?>
			<p class="hint">This is your description of the app in the App Store.</p>
		</div>
	</section>
	<?php if ($entry->isNewRecord) : ?>
		<section class="cf">
			<?php echo $form->labelEx($entry,'default_style'); ?>
			<div>
				<?php echo $form->dropDownList($entry, 'default_style', $entry->getStyles()); ?>
				<?php echo $form->error($entry,'default_style'); ?>
			</div>
		</section>
	<?php endif; ?>
	<div class="button">
		<?php echo YsaHtml::submitButton($entry->isNewRecord ? 'Create' : 'Save', array('class' => 'blue')); ?>
	</div>
	<?php $this->endWidget(); ?>
</div>