<?php $form = $this->beginWidget('YsaMemberForm', array(
    'id'=>'submit-step-form',
	'action' => array('application/saveStep/step/submit/'),
)); ?>	


<?php if ($this->member()->application->submitted()) : ?>
	<section class="part submit shadow-box">
		<label class="title">Managing changes</label>
		<p>Take a quick look through each section and make sure all of your elements and files are set and uploaded correctly. You can now save your app to work on later or you can proceed to preview your app and submit it to YSA!<p>
	</section>
	<?php echo $form->hiddenField($model, 'finish', array()); ?>
	<div class="save">
		<?php echo YsaHtml::submitButton('Save', array('class' => 'blue'));?>
	</div>
<?php else:?>
	<section class="part submit shadow-box">
		<label class="title">You’re Almost Done!</label>
		<p>Take a quick look through each section and make sure all of your elements and files are set and uploaded correctly. You can now save your app to work on later or you can proceed to preview your app and submit it to YSA!<p>
	</section>
	<?php echo $form->hiddenField($model, 'finish', array()); ?>
	<div class="save">
		<?php echo YsaHtml::link('Preview', '#', array('class' => 'btn small preview'));?>
		<?php echo YsaHtml::link('I want to play more later', array('application/'), array('class' => 'btn small')); ?>
		<?php echo YsaHtml::submitButton('Preview & Submit', array('class' => 'blue'));?>
	</div>
<?php endif; ?>

<?php $this->endWidget(); ?>