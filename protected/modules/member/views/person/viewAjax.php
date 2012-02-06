<div class="w" id="studio-person-view">
	<section class="box">
		<div class="box-title">
			<h3><?php echo $entry->name; ?></h3>
		</div>
		<div class="box-content">
			<div class="shadow-box cf">
				<figure>
					<?php echo $entry->photo(); ?>
				</figure>
				<div class="page">
					<?php echo $entry->description; ?>
				</div>
			</div>
		</div>
	</section>
</div> 