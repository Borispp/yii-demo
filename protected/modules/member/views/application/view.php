<div class="w" id="application">
	<section class="box">
		<div class="box-title">
			<h3><?php echo $app->name; ?></h3>
			<div class="box-title-button">
				<?php echo YsaHtml::link(
					'<span class="icon i_pencil"></span>Edit General Settings',
					array('application/edit/' . $app->id),
					array('class' => 'secondary iconed'));
				?>
			</div>
		</div>
		<div class="box-content">
			
			<div class="preview">
				<div class="ipad ipad900">
					<div class="wrap">
						<div class="content">
						</div>
					</div>
					<div class="home">
						<div>
							<span></span>
						</div>
					</div>
				</div>
			</div>
			
			<div class="cf info">
				<div class="shadow-box status">
					<h4><?php YsaHelpers::t('application', 'status_block_title')?></h4>
					<figure>
						<?php echo $app->icon();?>
					</figure>
					<p><?php echo $app->statusLabel(); ?></p>
					<span class="button"><?php echo YsaHtml::link('Change Settings', array('wizard'), array('class' => 'btn')); ?></span>
				</div>
				<?php if ($app->isPaid()):?>
				<div class="shadow-box submit">
					<h4><?php YsaHelpers::t('application', 'submit_block_title')?></h4>
					<p><?php YsaHelpers::t('application', 'submit_block_text')?></p>
					<?php if (!$app->submitted()) : ?>
						<span class="button"><?php echo YsaHtml::link(Yii::t('application', 'submit_button'), array('agreement'), array('class' => 'btn blue')); ?></span>
					<?php endif; ?>
				</div>
				<?php else:?>
				<div class="shadow-box submit">
					<h4><?php YsaHelpers::t('application', 'pay_block_title')?></h4>
					<p><?php YsaHelpers::t('application', 'pay_block_text')?></p>
					<span class="button"><?php echo YsaHtml::link(Yii::t('application', 'pay_button'), array('pay'), array('class' => 'btn blue')); ?></span>
				</div>
				<?php endif?>
				<?php if ($app->hasSupport()) : ?>
					<?php echo YsaHtml::link('Support Ticket', array('support'), array('class' => 'btn red-txt fr')); ?>
				<?php endif; ?>
			</div>
		</div>
	</section>
</div>