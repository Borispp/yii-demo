<div class="w" id="support-index">
	<section class="box">
		<div class="box-title">
			<h3><?php echo Yii::t('title', 'support'); ?></h3>
		</div>
		<div class="box-content cf">
			<div class="shadow-box fl tutorials">
				<a href="<?php echo Yii::app()->createUrl('member/help/')?>">
					<strong>
						<?php echo Yii::t('title', 'Tutorials'); ?>
					</strong>
					<em>
						<?php echo Yii::t('support', 'support_tutorials_caption'); ?>
					</em>
				</a>				
			</div>
			
			<div class="shadow-box fr helpdesk">
				<a href="<?php echo Yii::app()->settings->get('zendesk_url')?>" rel="external">
					<strong>
						<?php echo Yii::t('title', 'Helpdesk'); ?>
					</strong>
					<em>
						<?php echo Yii::t('support', 'support_helpdesk_caption'); ?>
					</em>
				</a>
			</div>
		</div>
	</section>
</div> 