<div class="form">
	<?php $form=$this->beginWidget('YsaMemberForm', array(
			'id'=>'studio-info-form',
			'enableAjaxValidation'=>false,
	)); ?>
	<section>
		<?php echo $form->labelEx($entry,'name'); ?>
		<div>
			<?php echo $form->textField($entry,'name', array('maxlength' => 100)); ?>
			<?php echo $form->error($entry,'name'); ?>
		</div>
	</section>
	<section>
		<?php echo $form->labelEx($entry,'blog_feed'); ?>
		<div>
			<?php echo $form->textField($entry,'blog_feed', array('maxlength' => 100)); ?>
			<?php echo $form->error($entry,'blog_feed'); ?>
		</div>
	</section>
	<section>
		<?php echo $form->labelEx($entry,'twitter_feed'); ?>
		<div>
			<?php echo $form->textField($entry,'twitter_feed', array('maxlength' => 100)); ?>
			<?php echo $form->error($entry,'twitter_feed'); ?>
		</div>
	</section>

	<section>
		<?php echo $form->labelEx($entry,'facebook_feed'); ?>
		<div>
			<?php echo $form->textField($entry,'facebook_feed', array('maxlength' => 100)); ?>
			<?php echo $form->error($entry,'facebook_feed'); ?>
		</div>
	</section>

	<section class="button">
		<?php echo YsaHtml::submitButton('Save'); ?>
	</section>
	<?php $this->endWidget(); ?>
</div>