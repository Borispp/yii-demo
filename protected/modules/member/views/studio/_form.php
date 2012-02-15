<div class="form standart-form shadow-box">
	<?php $form=$this->beginWidget('YsaMemberForm', array(
			'id'=>'studio-info-form',
			'action' => array('saveGeneralInfo')
	)); ?>
	<?php
		$fields = array('blog_feed', 'twitter_feed', 'facebook_feed',);
		//'order_link'
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
		<?php echo YsaHtml::submitButton('Save', array('class' => 'blue', 'data-loading' => 'Loading', 'data-value' => 'Save')); ?>
	</div>
	<?php $this->endWidget(); ?>
</div>