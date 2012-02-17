<div class="shadow-box submit" id="box-<?php echo $app->status()?>">
	<h4><?php YsaHelpers::t('application', $app->status().'_block_title')?></h4>
	<p><?php YsaHelpers::t('application', $app->status().'_block_text')?></p>
	<?php if ($app->filled() && !$app->isPaid()):?>
		<span class="button">
			<?php echo YsaHtml::link(Yii::t('application', 'pay_button'), array('pay'), array('class' => 'btn blue')); ?>
		</span>
	<?php elseif ($app->isPaid() && !$app->submitted()):?>
		<span class="button">
			<?php echo YsaHtml::link(Yii::t('application', 'submit_button'), array('agreement'), array('class' => 'btn blue')); ?>
		</span>
	<?php elseif ($app->running() && $app->option('appstore_link')):?>
		<span class="button">
			<?php echo YsaHtml::link(Yii::t('application', 'appstore_button'), $app->option('appstore_link'),
			array('class' => 'btn blue', 'target' => '_blank')); ?>
		</span>
	<?php elseif ($app->hasSupport()):?>
		<span class="button">
			<?php echo YsaHtml::link('Support Ticket', array('support'), array('class' => 'btn red-txt fr')); ?>
		</span>
	<?php endif?>
</div>

