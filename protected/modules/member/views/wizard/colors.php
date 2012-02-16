<?php $form = $this->beginWidget('YsaMemberForm', array(
	'id'=>'colors-step-form',
	'action' => array('application/saveStep/step/colors/'),
)); ?>

<section class="part group studio-bg shadow-box" id="wizard-box-studio_bg">
		<label class="title cf">
			<span><?php echo Yii::t('general', 'Studio Background'); ?>&nbsp;&nbsp;</span>
			<?php if ($model->help('studio')) : ?>
				<a href="<?php echo $model->help('studio') ?>" class="help fancybox" title="<?php echo Yii::t('general', 'Studio Background'); ?>">?</a>
			<?php endif; ?>
		</label>
	<?php
		$this->renderPartial('/wizard/_selector', array(
			'radioName' => 'studio_bg',
			'radioValues' => array('color' => 'Color', 'image' => 'Image'),
			'colorName' => 'studio_bg_color',
			'imageName' => 'studio_bg_image',
			'info'  => 'The top part of your Studio page can be customized with an image or color. Must be 1024x768 pixels.',
			'form' => $form,
			'model' => $model,
		));
	?>
</section>
<section class="part group generic-bg shadow-box" id="wizard-box-generic_bg">
		<label class="title cf">
			<span><?php echo Yii::t('general', 'Event Page Background'); ?>&nbsp;&nbsp;</span>
			<?php if ($model->help('generic')) : ?>
				<a href="<?php echo $model->help('generic') ?>" class="help fancybox" title="<?php echo Yii::t('general', 'Generic Background'); ?>">?</a>
			<?php endif; ?>
		</label>
	<?php
		$this->renderPartial('/wizard/_selector', array(
			'radioName' => 'generic_bg',
			'radioValues' => array('color' => 'Color', 'image' => 'Image'),
			'colorName' => 'generic_bg_color',
			'imageName' => 'generic_bg_image',
			'info'  => 'Select or upload the background your Events page will be set against. Must be 1024x768 pixels.',
			'form' => $form,
			'model' => $model,
		));
	?>
</section>

<div class="cf"></div>

<div class="save">
	<?php echo YsaHtml::link('Preview', '#', array('class' => 'btn small preview'));?>
	<?php echo YsaHtml::submitButton('Save & Continue', array('class' => 'blue'));?>
</div>

<?php $this->endWidget(); ?>