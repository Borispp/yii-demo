<div class="w" id="support-index">
	<section class="box">
		<div class="box-title">
			<h3><?php echo Yii::t('title', 'support'); ?></h3>
		</div>
		<div class="box-content cf">
			<div class="shadow-box fl">
				<?php echo YsaHtml::link('Have a question?', array('help/')); ?>
			</div>
			
			<div class="shadow-box fr">
				<?php echo YsaHtml::link('Have a question?', Yii::app()->settings->get('zendesk_url'), array('rel' => 'external')); ?>
			</div>
		</div>
	</section>
</div> 