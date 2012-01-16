<div class="form standart-form shadow-box">
	<?php $form=$this->beginWidget('YsaMemberForm', array(
			'id'=>'studio-info-form',
			'enableAjaxValidation'=>false,
	)); ?>
	<?php
		$fields = array('name', 'blog_feed', 'twitter_feed', 'facebook_feed', 'order_link');
	?>
	<?php foreach ($fields as $field) : ?>
		<section class="cf">
			<?php echo $form->labelEx($entry, $field); ?>
			<div>
				<?php echo $form->textField($entry, $field, array('maxlength' => 100)); ?>
				<?php echo $form->error($entry, $field); ?>
			</div>
		</section>
	<?php endforeach; ?>
	<div class="button">
		<?php echo YsaHtml::submitButton('Save', array('class' => 'blue')); ?>
	</div>
	<?php $this->endWidget(); ?>
</div>