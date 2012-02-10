<div class="w">
	<section class="box">
		<div class="box-title">
			<h3>Add Link</h3>
		</div>
		<div class="box-content">
			<div class="shadow-box">
				
				<?php if (StudioLink::TYPE_CUSTOM == $type) : ?>
					<p><?php echo Yii::t('notice', 'studio_add_link_notice'); ?></p>
				<?php endif;?>
				
				<?php $this->renderPartial('_form', array(
					'entry' => $entry,
					'type'	=> $type,
				)); ?>
			</div>
		</div>
	</section>
</div>