<div class="w">	
	<section class="box">
		<div class="box-title">
			<h3><?php echo $event->name; ?></h3>
		</div>
		<div class="box-content">
			<?php $this->renderPartial('_form', array(
				'entry' => $entry,
			)); ?>
		</div>
	</section>
</div>