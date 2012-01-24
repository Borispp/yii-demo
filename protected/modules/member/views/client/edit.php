<div class="w" id="client-edit">
	<section class="box">
		<div class="box-title">
			<h3>Edit Client</h3>
		</div>
		<div class="box-content">
			<div class="shadow-box">
				<?php $this->renderPartial('_form', array(
					'entry'		=> $entry,
					'events'	=> $events
				)); ?>
			</div>
		</div>
	</section>
</div>