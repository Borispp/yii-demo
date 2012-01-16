<div id="app-wizard" class="w body">
	<?php echo $event->sender->menu->run(); ?>


	<?php $form = $this->beginWidget('YsaMemberForm', array(
			'id'=>'fonts-step-form',
			'enableAjaxValidation'=>false,
			'htmlOptions'=>array('enctype' => 'multipart/form-data'),
	)); ?>

		<section>
			<?php echo $form->labelEx($model, 'main_font'); ?>
			<div>
				<?php echo $form->dropDownList($model, 'main_font', $model->getFontList()); ?>
				<?php echo $form->error($model,'main_font'); ?>
			</div>
		</section>

		<section>
			<?php echo $form->labelEx($model, 'main_font_color'); ?>
			<div>
				<?php echo $form->textField($model, 'main_font_color', array('class' => 'colors', 'readonly' => true)); ?>
				<?php echo $form->error($model,'main_font_color'); ?>
			</div>
		</section>

		<section>
			<?php echo $form->labelEx($model, 'second_font'); ?>
			<div>
				<?php echo $form->dropDownList($model, 'second_font', $model->getFontList()); ?>
				<?php echo $form->error($model,'second_font'); ?>
			</div>
		</section>

		<section>
			<?php echo $form->labelEx($model, 'second_font_color'); ?>
			<div>
				<?php echo $form->textField($model, 'second_font_color', array('class' => 'colors', 'readonly' => true)); ?>
				<?php echo $form->error($model,'second_font_color'); ?>
			</div>
		</section>

		<section class="btn">
			<?php echo YsaHtml::hiddenField('step', $event->step) ?>
			<?php echo YsaHtml::submitButton('Save & Continue');?>
		</section>

	<?php $this->endWidget(); ?>
</div>