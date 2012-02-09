<?php $form = $this->beginWidget('YsaMemberForm', array(
		'id'=>'fonts-step-form',
		'action' => array('application/saveStep/step/fonts/'),
)); ?>

	<section class="group part main-font shadow-box" id="wizard-box-main_font">
		
		<label class="title cf">
			<span><?php echo Yii::t('general', 'Main Font'); ?>&nbsp;&nbsp;</span>
			<?php if ($model->help('main')) : ?>
				<a href="<?php echo $model->help('main') ?>" class="help fancybox" title="<?php echo Yii::t('general', 'Main Font'); ?>">?</a>
			<?php endif; ?>
		</label>
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
				<?php echo $form->textField($model, 'main_font_color', array('class' => 'colors')); ?>
				<?php echo $form->error($model,'main_font_color'); ?>
			</div>
		</div>
	</section>

	<section class="group part second-font shadow-box" id="wizard-box-second_font">
		
		<label class="title cf">
			<span><?php echo Yii::t('general', 'Secondary Font'); ?>&nbsp;&nbsp;</span>
			<?php if ($model->help('second')) : ?>
				<a href="<?php echo $model->help('second') ?>" class="help fancybox" title="<?php echo Yii::t('general', 'Secondary Font'); ?>">?</a>
			<?php endif; ?>
		</label>
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
				<?php echo $form->textField($model, 'second_font_color', array('class' => 'colors')); ?>
				<?php echo $form->error($model,'second_font_color'); ?>
			</div>
		</div>
	</section>

	<div class="clearfix"></div>

	<div class="save">
		<?php echo YsaHtml::submitButton('Save & Continue', array('class' => 'blue'));?>
	</div>

<?php $this->endWidget(); ?>