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
			<span><?php echo Yii::t('general', 'Your iPad Icon'); ?>&nbsp;&nbsp;</span>
			<?php if ($model->help('icon')) : ?>
				<a href="<?php echo $model->help('icon') ?>" class="help fancybox" title="<?php echo Yii::t('general', 'iPad Icon'); ?>">?</a>
			<?php endif; ?>
		</label>
		<?php
			$this->renderPartial($locked ? '/wizard/_imagelock' : '/wizard/_uploader', array(
				'name'	=> 'icon',
				'model' => $model,
				'info'  => 'This is what will appear on your device. Must be 72x72 pixels. Use an iOS icon generator like this to make your icon shiny and professional.',
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
				'info'  => 'Design what icon will be seen in iTunes and on the App Store. Usually a larger version of your mobile icon (512x512 pixels).',
			));
		?>
	</section>

	<section class="part splash-bg shadow-box <?php echo $locked ? 'locked' : ''?>" id="wizard-box-splash_bg_image">
		<label class="title cf">
			<span><?php echo Yii::t('general', 'Splash'); ?>&nbsp;&nbsp;</span>
			<?php if ($model->help('splash')) : ?>
				<a href="<?php echo $model->help('splash') ?>" class="help fancybox" title="<?php echo Yii::t('general', 'Splash'); ?>">?</a>
			<?php endif; ?>
		</label>
		<?php
//			$this->renderPartial('/wizard/_selector', array(
//				'radioName' => 'splash_bg',
//				'radioValues' => array('color' => 'Color', 'image' => 'Image'),
//				'colorName' => 'splash_bg_color',
//				'imageName' => 'splash_bg_image',
//				'info'  => 'This is the image seen when your app first opens. Should be 1024x768 pixels to account for the navigation bar at the bottom.',
//				'form' => $form,
//				'model' => $model,
//			));
			$this->renderPartial($locked ? '/wizard/_imagelock' : '/wizard/_uploader', array(
				'name'	=> 'splash_bg_image',
				'model' => $model,
				'info'  => 'This is the image seen when your app first opens. Should be 1024x768 pixels to account for the navigation bar at the bottom.',
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
				'info'  => 'Your logo will appear at the top of the Events page. Click on the “?” to see it’s location on your app. Should be no larger than 400x400 pixels.',
			));
		?>
	</section>
	
	<div class="save">
		<?php echo YsaHtml::link('Preview', '#', array('class' => 'btn small preview'));?>
		<?php echo YsaHtml::submitButton('Save & Continue', array('class' => 'blue'));?>
	</div>

<?php $this->endWidget(); ?>

<div class="cf"></div>