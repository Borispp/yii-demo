<div class="w">
	<h2><?php echo $entry->name; ?></h2>
	
	<figure>
		<?php echo $entry->photo(); ?>
	</figure>
	
	<div class="content">
		<?php echo $entry->description; ?>
	</div>
</div>