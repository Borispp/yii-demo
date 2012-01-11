<div id="app-wizard" class="w">
	<?php echo $event->sender->menu->run(); ?>
	<h2>Upload your logo</h2>
	<?php $form = $this->beginWidget('YsaMemberForm', array(
			'id'=>'logo-step-form',
			'enableAjaxValidation'=>false,
			'htmlOptions'=>array('enctype' => 'multipart/form-data'),
		)); ?>
	<section>
		<?php echo $form->labelEx($model, 'style'); ?>
		<div>
			<?php echo $form->dropDownList($model, 'style', $model->getStylesList()); ?>
			<?php echo $form->error($model,'style'); ?>
		</div>
	</section>
	<section>
		<?php echo $form->labelEx($model, 'icon'); ?>
		<p>You should use some iOS icon generator like <a href="http://wizardtoolkit.com/shooter/iPhone-Icon-Generator" target="_blank">this</a> to make you icon shiny and professional</p>
		<div class="value value-image">
			<?php
				$this->renderPartial($model->icon ? '_image' : '_upload', array(
					'name'	=> 'icon',
					'image' => $model->icon,
				));
			?>
		</div>
	</section>
	<section>
		<?php echo $form->labelEx($model, 'itunes_logo'); ?>
		<div class="value value-image">			
			<?php
				$this->renderPartial($model->itunes_logo ? '_image' : '_upload', array(
					'name'	=> 'itunes_logo',
					'image' => $model->itunes_logo,
				));
			?>
		</div>
	</section>
	<section>
		<?php echo $form->labelEx($model, 'logo'); ?>
		<div class="value value-image">
			<?php
				$this->renderPartial($model->logo ? '_image' : '_upload', array(
					'name'	=> 'logo',
					'image' => $model->logo,
				));
			?>
		</div>
	</section>
	<section class="group">
		<div class="switcher">
			<?php echo $form->radioButtonList($model, 'splash_bg', array('image' => 'Background Image', 'color' => 'Background Color'), array('separator' => '', 'template' => '<span>{input} {label}</span>'));?>
		</div>
		<section class="image">
			<?php echo $form->labelEx($model, 'splash_bg_image'); ?>
			<div class="value value-image">
				<?php
					$this->renderPartial($model->splash_bg_image ? '_image' : '_upload', array(
						'name'	=> 'splash_bg_image',
						'image' => $model->splash_bg_image,
					));
				?>
			</div>
		</section>
		<section class="color">
			<?php echo $form->labelEx($model, 'splash_bg_color'); ?>
			<div class="value">
				<?php echo $form->textField($model, 'splash_bg_color', array('class' => 'colors', 'readonly' => true)); ?>
				<?php echo $form->error($model,'splash_bg_color'); ?>
			</div>
		</section>
	</section>
	<section class="btn">
		<?php echo YsaHtml::hiddenField('step', $event->step) ?>
		<?php echo YsaHtml::submitButton('Save & Continue');?>
	</section>
	<?php $this->endWidget(); ?>
</div>



<?php /*
<?php echo $form->fileField($model, 'icon', array()); ?>
<?php echo $form->error($model,'icon'); ?>
*/?>
<?php /*
<?php if ($model->icon) : ?>
<p><img src="<?php echo $model->itunes_logo['url']; ?>" alt="" /></p>
<?php endif; ?>
<?php echo $form->fileField($model, 'itunes_logo', array()); ?>
<?php echo $form->error($model,'itunes_logo'); ?>
*/?>
<?php /*
<?php if ($model->logo) : ?>
<p><img src="<?php echo $model->logo['url']; ?>" alt="" /></p>
<?php endif; ?>
<?php echo $form->fileField($model, 'logo', array()); ?>
<?php echo $form->error($model,'logo'); ?>
*/ ?>
<?php /*
<?php if ($model->splash_bg_image) : ?>
<p><img src="<?php echo $model->splash_bg_image['url']; ?>" alt="" /></p>
<?php endif; ?>
<?php echo $form->fileField($model, 'splash_bg_image', array()); ?>
<?php echo $form->error($model,'splash_bg_image'); ?>
*/?>