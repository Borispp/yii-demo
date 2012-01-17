<div class="w body" id="member-area">
	<div class="widgets g4">
		<section class="box widget" id="widget_quick_info">
			<div class="box-title">
				<h3>Quick Information</h3>
			</div>
			<div class="box-content">
				<ul>
					<li><span><?php echo count($this->member()->events); ?></span> Events</li>
					<li><span><?php echo isset($this->member()->application) ? count($this->member()->application->clients) : 0 ?></span> Clients</li>
				</ul>
			</div>
		</section>
		<section class="box widget" id="widget_quick_access">
			<div class="box-title">
				<h3>Quick Access</h3>
			</div>
			<div class="box-content">
				<ul>
					<li><?php echo YsaHtml::link('Create New Event'); ?></li>
					<li><?php echo YsaHtml::link('Add New Event'); ?></li>
					<li><?php echo YsaHtml::link('Application Wizard'); ?></li>
				</ul>
			</div>
		</section>
	</div>
	
	<div class="widgets g4">
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
		<section class="box widget" id="widget_latest_comments">
			<div class="box-title">
				<h3>Latest Comments</h3>
			</div>
			<div class="box-content">
				<ul>
					<li><span><?php echo count($this->member()->events); ?></span> Events</li>
					<li><span><?php echo isset($this->member()->application) ? count($this->member()->application->clients) : 0 ?></span> Clients</li>
				</ul>
			</div>
		</section>
		<section class="box widget number-widget" id="widget_latest_registered">
			<div class="box-title">
				<h3>Latest Registered Clients</h3>
			</div>
			<div class="box-content">
				<ul>
					<li>Client</li>
				</ul>
			</div>
		</section>
	</div>
	<div class="cf"></div>
</div>