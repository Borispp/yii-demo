<div class="w" id="application">
	<section class="box">
		<div class="box-title">
			<h3>Your Application</h3>
		</div>
		<div class="box-content">
			<div class="shadow-box">
				<?php if ($app->hasSupport()) : ?>
					<?php echo YsaHtml::link('Support Ticket', array('support'), array('class' => 'btn red fr')); ?>
				<?php endif; ?>
				<p>Current Application Status: <strong><?php echo $app->memberState(); ?></strong></p>
				<p class="warning">Warning! You cannot change logo after application submission. Please follow our rules correctly!</p>
				
				<div class="buttons">
					<?php echo YsaHtml::link('Settings Wizard', array('wizard'), array('class' => 'btn blue')); ?>
					<?php if ($app->filled()) : ?>
						<?php echo YsaHtml::link('Preview Settings', array('settings'), array('class' => 'btn')); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
</div>