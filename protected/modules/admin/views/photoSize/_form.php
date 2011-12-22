<div class="form">
	<?php $form=$this->beginWidget('YsaAdminForm', array(
			'id'=>'photo-size-form',
			'enableAjaxValidation'=>false,
	)); ?>
	<fieldset>
		<label>General Information</label>

		<section>
			<?php echo $form->labelEx($entry,'title'); ?>
			<div>
				<?php echo $form->textField($entry,'title',array('size'=>50,'maxlength'=>50, 'class' => 'w_50')); ?>
				<?php echo $form->error($entry,'title'); ?>
			</div>
		</section>

		<section>
			<label>Size</label>
			<div>
				<?php echo $form->textField($entry,'width',array('size'=>50,'maxlength'=>3, 'class' => 'w_5')); ?>
				<span>X</span>
				<?php echo $form->textField($entry,'height',array('size'=>50,'maxlength'=>3, 'class' => 'w_5')); ?>
				<span>(in)</span>
				<?php echo $form->error($entry,'width'); ?>
				<?php echo $form->error($entry,'height'); ?>
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
				<?php echo $form->textArea($entry,'description', array(
					'data-autogrow' => 'true',
					'rows'          => 5,
				)); ?>
				<?php echo $form->error($entry,'description'); ?>
			</div>
		</section>

	</fieldset>
	<?php echo YsaHtml::adminSaveSection();?>
	<?php $this->endWidget(); ?>
</div>