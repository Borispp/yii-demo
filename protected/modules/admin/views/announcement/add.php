<div class="g12">
	<div class="form">
		<?php $form= $this->beginWidget('YsaAdminForm', array(
			'id'                   => 'notification-form',
			'enableAjaxValidation' => false,
		)); ?>
		<fieldset>
			<label>Message</label>
			<section>
				<?php echo $form->labelEx($announcement,'message*'); ?>
				<div>
					<?php echo $form->textArea($announcement,'message', array(
						'data-autogrow' => 'true',
						'rows'          => 3,
					)); ?>
					<?php echo $form->error($announcement,'message'); ?>
				</div>
			</section>
		</fieldset>
		<?php echo YsaHtml::adminSaveSection();?>
		<?php $this->endWidget(); ?>
	</div>
</div>
