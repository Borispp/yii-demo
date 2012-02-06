<div class="g12">
	<div class="form">
		<?php $form=$this->beginWidget('YsaAdminForm', array(
			'id'=>'translation-category-form',
		)); ?>
		<fieldset>
			<label>General Information</label>
			<section>
				<?php echo $form->labelEx($entry,'name'); ?>
				<div>
					<?php echo $form->textField($entry,'name', array('maxlength'=>32)); ?>
					<?php echo $form->error($entry,'name'); ?>
				</div>
			</section>
		</fieldset>
		<?php echo YsaHtml::adminSaveSection();?>
		<?php $this->endWidget(); ?>
	</div>
</div>