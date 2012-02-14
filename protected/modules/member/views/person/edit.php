<div class="w" id="person-edit" data-personid="<?php echo $entry->id?>">
	<section class="box">
		<div class="box-title">
			<h3>Edit Shooter</h3>
		</div>
		<div class="box-content">
			<div class="shadow-box">
				<?php $this->renderPartial('_form', array(
					'entry' => $entry,
				)); ?>
			</div>
		</div>
	</section>
</div>