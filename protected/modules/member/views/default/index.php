<div class="w body" id="member-area">
	<div class="widgets g4">
		<section class="box widget" id="widget_quick_info">
			<div class="box-title">
				<h3>Quick Information</h3>
			</div>
			<div class="box-content">
				<ul>
					<li><span><?php echo count($this->member()->events); ?></span> Events</li>
					<li><span><?php echo count($this->member()->clients)?></span> Clients</li>
				</ul>
			</div>
		</section>
		<section class="box widget" id="widget_statistics">
			<div class="box-title">
				<h3>Statistics</h3>
			</div>
			<div class="box-content">
				some statistics goes here
			</div>
		</section>
	</div>
	<div class="widgets g4">
		<section class="box widget" id="widget_application">
			<div class="box-title">
				<h3>Application Quick Access</h3>
			</div>
			<div class="box-content">
				<?php if ($this->member()->application) : ?>
					<p class="c"><?php echo YsaHtml::link('Details', array('application/'), array('class' => 'btn')); ?> <?php echo YsaHtml::link('Change', array('application/wizard/'), array('class' => 'btn')); ?></p>
					<?php if ($this->member()->application->hasSupport()) : ?>
						<p class="c"><?php echo YsaHtml::link('Support Ticket', array('application/support/'), array('class' => 'btn red-txt small')); ?></p>
					<?php endif; ?>
				<?php else:?>
					<p class="c"><?php echo YsaHtml::link('Create Application', array('application/create/'), array('class' => 'btn')); ?></p>	
				<?php endif; ?>
			</div>
		</section>
		<section class="box widget" id="widget_latest_comments">
			<div class="box-title">
				<h3>Latest Comments</h3>
			</div>
			<div class="box-content">
				<ul>
					<li class="empty-list">No Comments</li>
				</ul>
			</div>
		</section>
	</div>
	<div class="widgets g4">
		<section class="box widget number-widget" id="widget_latest_events">
			<div class="box-title">
				<h3>Latest Events</h3>
			</div>
			<div class="box-content">
				<ul class="list">
					<?php if (count($this->member()->events)) : ?>
						<?php foreach($this->member()->events as $entry):?>
							<li><?php echo YsaHtml::link($entry->name, array('event/view/' . $entry->id), array()); ?></li>
						<?php endforeach?>
					<?php else:?>
						<li class="empty-list">No Events</li>
					<?php endif;?>
				</ul>
				<div class="box-button">
					<?php if (count($this->member()->events)) : ?>
						<?php echo YsaHtml::link('View All', array('event/'), array('class' => 'btn small')); ?>
					<?php endif;?>
					<?php echo YsaHtml::link('Create New Event', array('event/add/'), array('class' => 'btn small')); ?>
				</div>
			</div>
		</section>
		
		<section class="box widget number-widget" id="widget_latest_registered">
			<div class="box-title">
				<h3>Latest Registered Clients</h3>
			</div>
			<div class="box-content">
				<ul class="list">
					<?php if (count($this->member()->clients)) : ?>
						<?php foreach($this->member()->clients as $entry):?>
							<li><?php echo YsaHtml::link($entry->name, array('client/view/' . $entry->id), array()); ?></li>
						<?php endforeach?>
					<?php else:?>
						<li class="empty-list">No Clients</li>
					<?php endif;?>
				</ul>
				<div class="box-button">
					<?php if (count($this->member()->clients)) : ?>
						<?php echo YsaHtml::link('View All', array('client/'), array('class' => 'btn small')); ?>
					<?php endif;?>
					<?php echo YsaHtml::link('Add New Client', array('client/add/'), array('class' => 'btn small')); ?>
				</div>
			</div>
		</section>
	</div>
	<div class="cf"></div>
</div>