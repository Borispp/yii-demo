<?php $form = $this->beginWidget('YsaMemberForm', array(
		'id'=>'logo-step-form',
		'action' => array('application/saveStep/step/logo/'),
	)); ?>

	<section class="part style">
		<?php echo $form->labelEx($model, 'style', array('class' => 'title')); ?>
		<?php foreach ($model->getStylesList() as $kStyle => $style) : ?>
			<a href="#" data-style="<?php echo $kStyle; ?>" class="<?php echo $kStyle; ?><?php echo $kStyle == $model->style ? ' selected' : ''?>"><?php echo $style ?></a>
		<?php endforeach; ?>
		<?php echo $form->hiddenField($model, 'style'); ?>
	</section>

	<section class="part icons">
		<?php echo $form->labelEx($model, 'icon', array('class' => 'title')); ?>
		<?php
			$this->renderPartial('/wizard/_uploader', array(
				'name'	=> 'icon',
				'model' => $model,
				'info'  => 'You should use some iOS icon generator like <a href="http://wizardtoolkit.com/shooter/iPhone-Icon-Generator" target="_blank">this</a> to make you icon shiny and professional',
			));
		?>
	</section>

	<section class="part itunes-logo">
		<?php echo $form->labelEx($model, 'itunes_logo', array('class' => 'title')); ?>
		<?php
			$this->renderPartial('/wizard/_uploader', array(
				'name'	=> 'itunes_logo',
				'model' => $model,
				'info'  => 'You should use some iOS icon generator like <a href="http://wizardtoolkit.com/shooter/iPhone-Icon-Generator" target="_blank">this</a> to make you icon shiny and professional',
			));
		?>
	</section>

	<section class="part group splash-bg">
		<label class="title">Background</label>
		<?php
			$this->renderPartial('/wizard/_selector', array(
				'radioName' => 'splash_bg',
				'radioValues' => array('color' => 'Color', 'image' => 'Image'),
				'colorName' => 'splash_bg_color',
				'imageName' => 'splash_bg_image',
				'info'  => 'You should use some iOS icon generator like <a href="http://wizardtoolkit.com/shooter/iPhone-Icon-Generator" target="_blank">this</a> to make you icon shiny and professional',
				'form' => $form,
				'model' => $model,
			));
		?>
	</section>

	<section class="part logo">
		<?php echo $form->labelEx($model, 'logo', array('class' => 'title')); ?>
		<?php
			$this->renderPartial('/wizard/_uploader', array(
				'name'	=> 'logo',
				'model' => $model,
				'info'  => 'You should use some iOS icon generator like <a href="http://wizardtoolkit.com/shooter/iPhone-Icon-Generator" target="_blank">this</a> to make you icon shiny and professional',
			));
		?>
	</section>
	
	<div class="save">
		<?php echo YsaHtml::submitButton('Save & Continue');?>
	</div>

<?php $this->endWidget(); ?>

<div class="cf"></div>