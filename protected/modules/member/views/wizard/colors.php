<div id="app-wizard" class="w">
	<?php echo $event->sender->menu->run(); ?>
	<?php $form = $this->beginWidget('YsaMemberForm', array(
		'id'=>'colors-step-form',
		'enableAjaxValidation'=>false,
		'htmlOptions'=>array('enctype' => 'multipart/form-data'),
	)); ?>
	
	<section class="group">
		<div class="switcher">
			<?php echo $form->radioButtonList($model, 'studio_bg', array('image' => 'Background Image', 'color' => 'Background Color'), array('separator' => '', 'template' => '<span>{input} {label}</span>', 'selected' => 'image'));?>
		</div>
		<section class="image">
			<?php echo $form->labelEx($model, 'studio_bg_image'); ?>
			<div class="value value-image">
				<?php
					$this->renderPartial($model->studio_bg_image ? '_image' : '_upload', array(
						'name'	=> 'studio_bg_image',
						'image' => $model->studio_bg_image,
					));
				?>
			</div>
		</section>
		<section class="color">
			<?php echo $form->labelEx($model, 'studio_bg_color'); ?>
			<div>
				<?php echo $form->textField($model, 'studio_bg_color', array('class' => 'colors', 'readonly' => true)); ?>
				<?php echo $form->error($model,'studio_bg_color'); ?>
			</div>
		</section>
	</section>
	
	<section class="group">
		<div class="switcher">
			<?php echo $form->radioButtonList($model, 'generic_bg', array('image' => 'Background Image', 'color' => 'Background Color'), array('separator' => '', 'template' => '<span>{input} {label}</span>', 'checked' => 'image'));?>
		</div>
		<section class="image">
			<?php echo $form->labelEx($model, 'generic_bg_image'); ?>
			<div class="value value-image">
				<?php
					$this->renderPartial($model->generic_bg_image ? '_image' : '_upload', array(
						'name'	=> 'generic_bg_image',
						'image' => $model->generic_bg_image,
					));
				?>
			</div>
		</section>
		<section class="color">
			<?php echo $form->labelEx($model, 'generic_bg_color'); ?>
			<div>
				<?php echo $form->textField($model, 'generic_bg_color', array('class' => 'colors', 'readonly' => true)); ?>
				<?php echo $form->error($model,'generic_bg_color'); ?>
			</div>
		</section>
	</section>
	
	<section class="btn">
		<?php echo YsaHtml::hiddenField('step', $event->step) ?>
		<?php echo YsaHtml::submitButton('Save & Continue');?>
	</section>

	<?php $this->endWidget(); ?>

</div>


<?/*
<div>
<p><img src="<?php echo $model->studio_bg_image['url']; ?>" alt="" /></p>
<?php echo $form->fileField($model, 'studio_bg_image', array()); ?>
<?php echo $form->error($model,'studio_bg_image'); ?>
</div>
*/?>
<?/*
<div>
<p><img src="<?php echo $model->generic_bg_image['url']; ?>" alt="" /></p>
<?php echo $form->fileField($model, 'generic_bg_image', array()); ?>
<?php echo $form->error($model,'generic_bg_image'); ?>
</div>	
*/?>