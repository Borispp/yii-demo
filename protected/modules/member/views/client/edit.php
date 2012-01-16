<div class="w">
	<section class="box">
		<div class="box-title">
			<h3>Edit Client</h3>
		</div>
		<div class="box-content">
	<?php $this->renderPartial('_form', array(
		'entry'		=> $entry,
		'events'	=> $events
	)); ?>
		</div>
	</section>
</div>