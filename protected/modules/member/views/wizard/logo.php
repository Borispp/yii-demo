<?php $form = $this->beginWidget('YsaMemberForm', array(
		'id'=>'logo-step-form',
		'action' => array('application/saveStep/step/logo/'),
	)); ?>

	<section class="part style shadow-box" id="wizard-box-style">
		<?php if (!$model->style) {
			$model->style = $model->defaultStyle();
		 }?>
		
		<label class="title cf">
			<span><?php echo Yii::t('general', 'Style'); ?>&nbsp;&nbsp;</span>
			<?php if ($model->help('style')) : ?>
				<a href="<?php echo $model->help('style') ?>" class="help fancybox" title="<?php echo Yii::t('general', 'Style'); ?>">?</a>
			<?php endif; ?>
		</label>
		
		<?php foreach ($model->getStylesList() as $kStyle => $style) : ?>
			<a href="#" data-style="<?php echo $kStyle; ?>" class="styl <?php echo $kStyle; ?><?php echo $kStyle == $model->style ? ' selected' : ''?>"><?php echo $style ?></a>
		<?php endforeach; ?>
		<?php echo $form->hiddenField($model, 'style'); ?>
	</section>
	<section class="part icons shadow-box <?php echo $locked ? 'locked' : ''?>" id="wizard-box-icon">
		<label class="title cf">
			<span><?php echo Yii::t('general', 'iPad Icon'); ?>&nbsp;&nbsp;</span>
			<?php if ($model->help('icon')) : ?>
				<a href="<?php echo $model->help('icon') ?>" class="help fancybox" title="<?php echo Yii::t('general', 'iPad Icon'); ?>">?</a>
			<?php endif; ?>
		</label>
		<?php
			$this->renderPartial($locked ? '/wizard/_imagelock' : '/wizard/_uploader', array(
				'name'	=> 'icon',
				'model' => $model,
				'info'  => 'You should use some iOS icon generator like <a href="http://wizardtoolkit.com/shooter/iPhone-Icon-Generator" target="_blank">this</a> to make you icon shiny and professional',
			));
		?>
	</section>
	<section class="part itunes-logo shadow-box <?php echo $locked ? 'locked' : ''?>" id="wizard-box-itunes_logo">
		<label class="title cf">
			<span><?php echo Yii::t('general', 'iTunes Logo'); ?>&nbsp;&nbsp;</span>
			<?php if ($model->help('itunes_logo')) : ?>
				<a href="<?php echo $model->help('itunes_logo') ?>" class="help fancybox" title="<?php echo Yii::t('general', 'iTunes Logo'); ?>">?</a>
			<?php endif; ?>
		</label>
		
		<?php
			$this->renderPartial($locked ? '/wizard/_imagelock' : '/wizard/_uploader', array(
				'name'	=> 'itunes_logo',
				'model' => $model,
				'info'  => 'You should use some iOS icon generator like <a href="http://wizardtoolkit.com/shooter/iPhone-Icon-Generator" target="_blank">this</a> to make you icon shiny and professional',
			));
		?>
	</section>

	<section class="part group splash-bg shadow-box" id="wizard-box-background">
		<label class="title cf">
			<span><?php echo Yii::t('general', 'Splash'); ?>&nbsp;&nbsp;</span>
			<?php if ($model->help('splash')) : ?>
				<a href="<?php echo $model->help('splash') ?>" class="help fancybox" title="<?php echo Yii::t('general', 'Splash'); ?>">?</a>
			<?php endif; ?>
		</label>
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

	<section class="part logo shadow-box <?php echo $locked ? 'locked' : ''?>" id="wizard-box-logo">
		<label class="title cf">
			<span><?php echo Yii::t('general', 'Logo'); ?>&nbsp;&nbsp;</span>
			<?php if ($model->help('logo')) : ?>
				<a href="<?php echo $model->help('logo') ?>" class="help fancybox" title="<?php echo Yii::t('general', 'Logo'); ?>">?</a>
			<?php endif; ?>
		</label>
		<?php
			$this->renderPartial($locked ? '/wizard/_imagelock' : '/wizard/_uploader', array(
				'name'	=> 'logo',
				'model' => $model,
				'info'  => 'You should use some iOS icon generator like <a href="http://wizardtoolkit.com/shooter/iPhone-Icon-Generator" target="_blank">this</a> to make you icon shiny and professional',
			));
		?>
	</section>
	
	<div class="save">
		<?php echo YsaHtml::submitButton('Save & Continue', array('class' => 'blue'));?>
	</div>

<?php $this->endWidget(); ?>

<div class="cf"></div>