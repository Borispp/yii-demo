<div class="form">
	<?php $form=$this->beginWidget('YsaForm', array(
			'id'=>'newsletter-subscribe-form',
	)); ?>
	<section>
		<?php echo $form->labelEx($newsletterForm,'name'); ?>
		<div>
			<?php echo $form->textField($newsletterForm,'name'); ?>
			<?php echo $form->error($newsletterForm,'name'); ?>
		</div>
	</section>
	<section>
		<?php echo $form->labelEx($newsletterForm,'email'); ?>
		<div>
			<?php echo $form->textField($newsletterForm,'email'); ?>
			<?php echo $form->error($newsletterForm,'email'); ?>
		</div>
	</section>
	<section class="button">
		<?php echo CHtml::submitButton('Subscribe'); ?>
	</section>
	<?php $this->endWidget(); ?>
</div>