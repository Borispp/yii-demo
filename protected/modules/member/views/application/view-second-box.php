<div class="shadow-box submit" id="box-<?php echo $app->status()?>">
	<?php /* application is created and filled */?>
	<?php if ($app->filled() && !$app->isPaid()):?>
		<h4><?php echo Yii::t('application', 'filled_block_title')?></h4>
		<p>
			<?php echo Yii::t('application', 'filled_block_text')?>
		</p>
		<span class="button">
			<?php echo YsaHtml::link(Yii::t('application', 'pay_button'), array('agreement'), array('class' => 'btn blue')); ?>
		</span>
	<?php /* application is filled and paid but not submitted */?>
	<?php elseif ($app->isPaid() && !$app->submitted()) :?>
		<h4><?php echo Yii::t('application', 'paid_block_title')?></h4>
		<p>
			<?php echo Yii::t('application', 'paid_block_text')?>
		</p>
		<span class="button">
			<?php echo YsaHtml::link(Yii::t('application', 'submit_button'), array('submit'), array('class' => 'btn blue')); ?>
		</span>
	<?php /* application is sent to appstore */?>
	<?php elseif ($app->isReady()):?>
			<h4><?php echo Yii::t('application', 'appstore_block_title')?></h4>
			<p>
				<?php echo Yii::t('application', 'appstore_block_text')?>
			</p>
	<?php /* application is rejected by appstore */?>
	<?php elseif ($app->rejected()):?>
		<h4><?php echo Yii::t('application', 'rejected_block_title')?></h4>
		<p>
			<?php echo Yii::t('application', 'rejected_block_text')?>
		</p>
		
	<?php elseif ($app->running()):?>
		<h4><?php echo Yii::t('application', 'running_block_title')?></h4>
		<p>
			<?php echo Yii::t('application', 'running_block_text')?>
		</p>
		<?php if ($app->option('appstore_link')):?>
			<span class="button">
				<?php echo YsaHtml::link(Yii::t('application', 'appstore_button'), $app->option('appstore_link'), array('class' => 'btn blue', 'target' => '_blank')); ?>
			</span>
		<?php endif;?>
	<?php /* application is approved by moderator */?>
	<?php elseif ($app->approved()):?>
		<h4><?php echo Yii::t('application', 'approved_block_title')?></h4>
		<p>
			<?php echo Yii::t('application', 'approved_block_text')?>
		</p>
	<?php /* application is submitted for appstore approval */?>
	<?php elseif ($app->submitted()):?>
		<?php if ($app->hasSupport()):?>
			<h4><?php echo Yii::t('application', 'submitted_block_support_title')?></h4>
			<p>
				<?php echo Yii::t('application', 'submitted_block_support_text')?>
			</p>
			<span class="button">
				<?php echo YsaHtml::link('Support Ticket', array('support'), array('class' => 'btn red-txt fr')); ?>
			</span>
		<?php else:?>
			<h4><?php echo Yii::t('application', 'submitted_block_title')?></h4>
			<p>
				<?php echo Yii::t('application', 'submitted_block_text')?>
			</p>
		<?php endif;?>
		
	<?php /* application is not approved by moderator and has support ticket*/?>

	
	
	
	
	
	<?php /* application is approved by appstore */?>
	
	
	
	<?php /* application is running properly */?>
	
	<?php /* application has support ticket */?>
	
	<?php endif?>
	<?/*
	<h4><?php echo Yii::t('application', $app->status().'_block_title')?></h4>
	<p><?php echo Yii::t('application', $app->status().'_block_text')?></p>
	
		
		<?php if ($app->filled() && !$app->isPaid()):?>
		<span class="button">
			<?php echo YsaHtml::link(echo Yii::t('application', 'pay_button'), array('pay'), array('class' => 'btn blue')); ?>
		</span>
	<?php elseif ($app->isPaid() && !$app->submitted()):?>
		<span class="button">
			<?php echo YsaHtml::link(echo Yii::t('application', 'submit_button'), array('agreement'), array('class' => 'btn blue')); ?>
		</span>
	<?php elseif ($app->running() && $app->option('appstore_link')):?>
		<span class="button">
			<?php echo YsaHtml::link(echo Yii::t('application', 'appstore_button'), $app->option('appstore_link'),
			array('class' => 'btn blue', 'target' => '_blank')); ?>
		</span>
	<?php elseif ($app->hasSupport()):?>
		<span class="button">
			<?php echo YsaHtml::link('Support Ticket', array('support'), array('class' => 'btn red-txt fr')); ?>
		</span>
	<?php endif?>
	 */?>
</div>

