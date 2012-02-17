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
			<div class="cf info">
				<div class="shadow-box status">
					<h4><?php YsaHelpers::t('application', 'status_block_title')?></h4>
					<figure>
						<?php echo $app->icon();?>
					</figure>
					<p><?php echo $app->statusLabel(); ?></p>
					<span class="button"><?php echo YsaHtml::link('Change Settings', array('wizard'), array('class' => 'btn')); ?></span>
				</div>

				<?php $this->renderPartial('view-second-box', array(
					'app' => $app
				))?>
			</div>
			
			<?php $this->renderPartial('_ipad-preview', array('app' => $app));?>
		</div>
	</section>
</div>