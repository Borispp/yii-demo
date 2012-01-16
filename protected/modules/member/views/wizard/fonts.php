<?php $form = $this->beginWidget('YsaMemberForm', array(
		'id'=>'fonts-step-form',
		'action' => array('application/saveStep/step/fonts/'),
)); ?>

	<section class="group part main-font">
		
		<label class="title">Main Font</label>
		<p>Please select the main font for your application. Font is used in headers, comments, etc.</p>
		<div class="font-style">
			<?php echo $form->labelEx($model, 'main_font'); ?>
			<div>
				<?php echo $form->dropDownList($model, 'main_font', $model->getFontList()); ?>
				<?php echo $form->error($model,'main_font'); ?>
			</div>
		</div>
		<div class="font-color">
			<?php echo $form->labelEx($model, 'main_font_color'); ?>
			<div class="color-selector">
				<?php echo $form->textField($model, 'main_font_color', array('class' => 'colors', 'readonly' => true)); ?>
				<?php echo $form->error($model,'main_font_color'); ?>
			</div>
		</div>
	</section>

	<section class="group part second-font">
		
		<label class="title">Secondary Font</label>
		
		<p>Please select the secondary font for your application. Font is used in notifications, navigation, etc.</p>
		
		<div class="font-style">
			<?php echo $form->labelEx($model, 'second_font'); ?>
			<div>
				<?php echo $form->dropDownList($model, 'second_font', $model->getFontList()); ?>
				<?php echo $form->error($model,'second_font'); ?>
			</div>
		</div>
		<div class="font-color">
			<?php echo $form->labelEx($model, 'second_font_color'); ?>
			<div class="color-selector">
				<?php echo $form->textField($model, 'second_font_color', array('class' => 'colors', 'readonly' => true)); ?>
				<?php echo $form->error($model,'second_font_color'); ?>
			</div>
		</div>
	</section>

	<div class="clearfix"></div>

	<div class="save">
		<?php echo YsaHtml::submitButton('Save & Continue', array('class' => 'blue'));?>
	</div>

<?php $this->endWidget(); ?>