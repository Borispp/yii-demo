<div class="form">
	<?php $form=$this->beginWidget('YsaForm', array(
			'id'=>'newsletter-subscribe-form',
	)); ?>
	<section>
		<div><?php echo $form->textField($newsletterForm,'name', array('placeholder' => 'NAME')); ?></div>
		<?php echo $form->error($newsletterForm,'name'); ?>
	</section>
	<section>
		<div><?php echo $form->textField($newsletterForm,'email', array('placeholder' => 'EMAIL')); ?></div>
		<?php echo $form->error($newsletterForm,'email'); ?>
	</section>
	<p class="r">
		<?php echo CHtml::submitButton('Subscribe', array('class' => 'blue')); ?>
	</p>
	<?php $this->endWidget(); ?>
</div>