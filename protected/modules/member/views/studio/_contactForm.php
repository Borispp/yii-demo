<div class="box-description">
	Each field has a limit of 100 characters & 2 lines to fit the contact area correctly.
</div> 

<div class="form standart-form">
	<?php $form=$this->beginWidget('YsaMemberForm', array(
			'id'=>'studio-contact-form',
			'action' => array('saveContact')
	)); ?>
	
	<section class="cf">
		<?php echo $form->labelEx($entry, 'name'); ?>
		<div>
			<?php echo $form->textField($entry, 'name', array('maxlength' => 100)); ?>
			<?php echo $form->error($entry, 'name'); ?>
		</div>
	</section>
	
	<section class="cf">
		<?php echo $form->labelEx($entry, 'address'); ?>
		<div>
			<?php echo $form->textArea($entry, 'address', array('cols' => 50, 'rows' => ContactForm::AREA_ROWS)); ?>
			<?php echo $form->error($entry, 'address'); ?>
		</div>
	</section>
	<section class="cf">
		<?php echo $form->labelEx($entry, 'phone'); ?>
		<div>
			<?php echo $form->textArea($entry, 'phone', array('cols' => 50, 'rows' => ContactForm::AREA_ROWS)); ?>
			<?php echo $form->error($entry, 'phone'); ?>
		</div>
	</section>
	<section class="cf">
		<?php echo $form->labelEx($entry, 'info'); ?>
		<div>
			<?php echo $form->textArea($entry, 'info', array('cols' => 50, 'rows' => ContactForm::AREA_ROWS)); ?>
			<?php echo $form->error($entry, 'info'); ?>
		</div>
	</section>
	<div class="button">
		<?php echo YsaHtml::submitButton('Save', array('class' => 'blue', 'data-loading' => 'Saving', 'data-value' => 'Save')); ?>
	</div>
	<?php $this->endWidget(); ?>
</div>