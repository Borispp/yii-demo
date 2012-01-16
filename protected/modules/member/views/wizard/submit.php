<?php $form = $this->beginWidget('YsaMemberForm', array(
    'id'=>'submit-step-form',
	'action' => array('application/saveStep/step/submit/'),
)); ?>	
	<section class="part submit">
		<label class="title">You're almost there!</label>
		<p>Do your layouts deserve better than Lorem Ipsum? Apply as an art director and team up with the best copywriters at Jung von Matt: www.jvm.com/jobs/lipsum</p>
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam sed erat ut justo interdum viverra eu non nisi.</p>
	</section>

	<?php echo $form->hiddenField($model, 'finish', array()); ?>
	<div class="save">
		<?php echo YsaHtml::link('Submit Later', array('application/'), array('class' => 'btn small')); ?>
		
		<?php echo YsaHtml::submitButton('Finish & Submit', array('class' => 'blue'));?>
	</div>
<?php $this->endWidget(); ?>