<div class="w" id="application">
	<section class="box">
		<div class="box-title">
			<h3><?php echo $app->name; ?></h3>
			<div class="box-title-button">
				<?php echo YsaHtml::link('<span class="icon i_pencil"></span>Edit General Settings', array('application/edit/' . $app->id), array('class' => 'secondary iconed')); ?>
			</div>
		</div>
		<div class="box-content">
			<div class="cf info">
				<div class="shadow-box status">
					<h4>Current Application Status</h4>
					
					<figure>
						<?php echo $app->icon();?>
					</figure>
					
					<p><?php echo $app->statusLabel(); ?></p>
					
					<span class="button"><?php echo YsaHtml::link('Change Settings', array('wizard'), array('class' => 'btn')); ?></span>
				</div>
				<div class="shadow-box submit">
					<h4>Submit your application</h4>
					
					<p>Donec lorem nunc, facilisis a adipiscing vel, pulvinar et elit. Nullam nec dolor ut quam venenatis posuere. In at libero vitae urna semper dictum a a augue.</p>
					
					<?php if (!$app->submitted()) : ?>
						<span class="button"><?php echo YsaHtml::link('Submit for Review', array('submit'), array('class' => 'btn blue')); ?></span>
					<?php endif; ?>
				</div>
				
				<?php if ($app->hasSupport()) : ?>
					<?php echo YsaHtml::link('Support Ticket', array('support'), array('class' => 'btn red-txt fr')); ?>
				<?php endif; ?>
			</div>
			
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
		</div>
	</section>
</div>