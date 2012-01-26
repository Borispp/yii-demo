<div class="w" id="application">
	<section class="box">
		<div class="box-title">
			<h3>Your Application</h3>
			<div class="box-title-button">
				<?php echo YsaHtml::link('<span class="icon i_brush"></span>Edit General Settings', array('application/edit/' . $app->id), array('class' => 'secondary iconed')); ?>
			</div>
		</div>
		<div class="box-content">
			<div class="shadow-box">
				<?php if ($app->hasSupport()) : ?>
					<?php echo YsaHtml::link('Support Ticket', array('support'), array('class' => 'btn red fr')); ?>
				<?php endif; ?>
				<p class="<?php echo $app->status()?>">Current Application Status: <strong><?php echo $app->statusLabel(); ?></strong></p>
				<p class="warning">Warning! You cannot change logo after application submission. Please follow our rules correctly!</p>
				<div class="buttons">
					<?php echo YsaHtml::link('Change Settings', array('wizard'), array('class' => 'btn')); ?>
					<?php if ($app->submitted()) : ?>
						<?php echo YsaHtml::link('Preview', array('preview'), array('class' => 'btn blue')); ?>
					<?php elseif ($app->filled()) : ?>
						<?php echo YsaHtml::link('Preview & Submit', array('preview'), array('class' => 'btn blue')); ?>
					<?php endif; ?>
				</div>
				
				<?/*
				<?php if (count($this->member()->tickets)) : ?>
					<h3>Support History</h3>
					<ul>
						<?php foreach ($this->member()->tickets as $ticket) : ?>
							<li><?php echo YsaHtml::link('Ticket #' . $ticket->code . ' from ' . $ticket->created('medium', 'short'), array('ticket/view/id/' . $ticket->id)); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				*/?>
			</div>
		</div>
	</section>
</div>