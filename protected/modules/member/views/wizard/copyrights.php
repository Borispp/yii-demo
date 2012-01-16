<?php $form = $this->beginWidget('YsaMemberForm', array(
		'id'=>'copyrights-step-form',
	'action' => array('application/saveStep/step/copyrights/'),
)); ?>
	<section class="part copyrights">
		<?php echo $form->labelEx($model, 'copyright', array('class' => 'title')); ?>
		<p>This information will show at the bottom of your app. Here's an example - <em>&copy; by YourStudioName. All rights reserved.</em></p>
		<div>
			<?php echo $form->textField($model, 'copyright'); ?>
			<?php echo $form->error($model,'copyright'); ?>
		</div>
	</section>

	<div class="save">
		<?php echo YsaHtml::submitButton('Save & Continue', array('class' => 'blue'));?>
	</div>

<?php $this->endWidget(); ?>