<div class="form cf">
	<?php $form=$this->beginWidget('YsaForm', array(
			'id'=>'newsletter-subscribe-form',
			'action' => array('newsletter/subscribe/'),
	)); ?>
	<section>
		<div><?php echo $form->textField($newsletterForm,'name', array('placeholder' => 'NAME', 'required' => 'required', 'title' => 'name')); ?></div>
		<?php echo $form->error($newsletterForm,'name'); ?>
	</section>
	<section>
		<div><?php echo $form->emailField($newsletterForm,'email', array('placeholder' => 'EMAIL', 'required' => 'required', 'title' => 'email')); ?></div>
		<?php echo $form->error($newsletterForm,'email'); ?>
	</section>
	<div id="newsletter-subscribe-form-errors"></div>
	<p class="fr">	
		<?php echo YsaHtml::submitLoadingButton('Subscribe', array('class' => 'blue')); ?>
	</p>
	<?php $this->endWidget(); ?>
</div>