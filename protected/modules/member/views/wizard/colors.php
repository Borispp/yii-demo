<?php $form = $this->beginWidget('YsaMemberForm', array(
	'id'=>'colors-step-form',
	'action' => array('application/saveStep/step/colors/'),
)); ?>

<section class="part group studio-bg">
	<label class="title">Studio Background</label>
	<?php
		$this->renderPartial('/wizard/_selector', array(
			'radioName' => 'studio_bg',
			'radioValues' => array('color' => 'Color', 'image' => 'Image'),
			'colorName' => 'studio_bg_color',
			'imageName' => 'studio_bg_image',
			'info'  => 'You should use some iOS icon generator like <a href="http://wizardtoolkit.com/shooter/iPhone-Icon-Generator" target="_blank">this</a> to make you icon shiny and professional',
			'form' => $form,
			'model' => $model,
		));
	?>
</section>

<section class="part group generic-bg">
	<label class="title">Generic Background</label>
	<?php
		$this->renderPartial('/wizard/_selector', array(
			'radioName' => 'generic_bg',
			'radioValues' => array('color' => 'Color', 'image' => 'Image'),
			'colorName' => 'generic_bg_color',
			'imageName' => 'generic_bg_image',
			'info'  => 'You should use some iOS icon generator like <a href="http://wizardtoolkit.com/shooter/iPhone-Icon-Generator" target="_blank">this</a> to make you icon shiny and professional',
			'form' => $form,
			'model' => $model,
		));
	?>
</section>

<div class="clearfix"></div>

<div class="save">
	<?php echo YsaHtml::submitButton('Save & Continue', array('class' => 'blue'));?>
</div>

<?php $this->endWidget(); ?>