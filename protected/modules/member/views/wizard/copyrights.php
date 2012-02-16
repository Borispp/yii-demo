<?php $form = $this->beginWidget('YsaMemberForm', array(
		'id'=>'copyrights-step-form',
		'action' => array('application/saveStep/step/copyrights/'),
)); ?>
<section class="part copyrights shadow-box <?php echo $locked ? 'locked' : ''?>" id="wizard-box-copyright">
	<?php echo $form->labelEx($model, 'copyright', array('class' => 'title')); ?>
	<p>This information will show at the bottom of your app. Here's an example - <em>&copy; by YourStudioName. All rights reserved.</em></p>
	<div>
		<?php if ($locked) : ?>
		<p><strong><?php echo $model->copyright; ?></strong></p>
		<?php else:?>
			<?php echo $form->textField($model, 'copyright'); ?>
			<?php echo $form->error($model,'copyright'); ?>
		<?php endif; ?>
	</div>
</section>
<div class="save">
	<?php echo YsaHtml::link('Preview', '#', array('class' => 'btn small preview'));?>
	<?php echo YsaHtml::submitButton('Save & Continue', array('class' => 'blue'));?>
</div>
<?php $this->endWidget(); ?>