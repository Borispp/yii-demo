<div id="app-wizard" class="w body">
	<?php echo $event->sender->menu->run(); ?>

	<?php $form = $this->beginWidget('YsaMemberForm', array(
			'id'=>'copyrights-step-form',
			'enableAjaxValidation'=>false,
			'htmlOptions'=>array('enctype' => 'multipart/form-data'),
	)); ?>
		<section>
			<?php echo $form->labelEx($model, 'copyright'); ?>
			<div>
				<?php echo $form->textField($model, 'copyright', array()); ?>
				<?php echo $form->error($model,'copyright'); ?>
			</div>
		</section>

		<section class="btn">
			<?php echo YsaHtml::hiddenField('step', $event->step) ?>
			<?php echo YsaHtml::submitButton('Save & Continue');?>
		</section>

	<?php $this->endWidget(); ?>
</div>