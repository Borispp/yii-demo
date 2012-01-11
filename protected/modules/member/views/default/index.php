<div class="w body" id="member-area">

	<div class="widgets g4">
		<div class="widget" id="widget_quick_info">
			<h3>Quick Information</h3>
			<div>
				<ul>
					<li><span><?php echo count($this->member()->events); ?></span> Events</li>
					<li><span><?php echo count($this->member()->application->clients); ?></span> Clients</li>
				</ul>
			</div>
		</div>
		<div class="widget" id="widget_quick_access">
			<h3>Quick Access</h3>
			<div>
				<ul>
					<li><?php echo YsaHtml::link('Create New Event'); ?></li>
					<li><?php echo YsaHtml::link('Add New Event'); ?></li>
					<li><?php echo YsaHtml::link('Application Wizard'); ?></li>
				</ul>
			</div>
		</div>
	</div>
	
	<div class="widgets g4">
		<div class="widget" id="widget_statistics">
			<h3>Statistics</h3>
			<div>
				some statistics goes here
			</div>
		</div>
	</div>
	
	<div class="widgets g4">
		<div class="widget" id="widget_latest_comments">
			<h3>Latest Comments</h3>
			<div>
				<ul>
					<li><span><?php echo count($this->member()->events); ?></span> Events</li>
					<li><span><?php echo count($this->member()->application->clients); ?></span> Clients</li>
				</ul>
			</div>
		</div>
		<div class="widget number-widget" id="widget_latest_registered">
			<h3>Latest Registered Clients</h3>
			<div>
				<ul>
					<li>Client</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="cf"></div>
</div>