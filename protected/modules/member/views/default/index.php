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
					<p class="c"><?php echo YsaHtml::link('Application Wizard', array('application/wizard/'), array('class' => 'btn')); ?></p>
					<?php if ($this->member()->application->hasSupport()) : ?>
						<p class="c"><?php echo YsaHtml::link('Support Ticket', array('support'), array('class' => 'btn red small')); ?></p>
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
					<?php foreach($this->member()->events as $entry):?>
						<li><?php echo YsaHtml::link($entry->name, array('event/view/' . $entry->id), array()); ?></li>
					<?php endforeach?>
				</ul>
				<div class="box-button">
					<li><?php echo YsaHtml::link('Create New Event', array('client/add/'), array('class' => 'btn small')); ?></li>
				</div>
			</div>
		</section>
		
		<section class="box widget number-widget" id="widget_latest_registered">
			<div class="box-title">
				<h3>Latest Registered Clients</h3>
			</div>
			<div class="box-content">
				<ul class="list">
					<?php foreach(Client::model()->findAllByMember($this->member()) as $obClient):?>
						<li><?php echo YsaHtml::link($obClient->name, array('client/view/' . $obClient->id), array()); ?></li>
					<?php endforeach?>
				</ul>
				<div class="box-button">
					<li><?php echo YsaHtml::link('Add New Client', array('client/add/'), array('class' => 'btn small')); ?></li>
				</div>
			</div>
		</section>
	</div>
	<div class="cf"></div>
</div>