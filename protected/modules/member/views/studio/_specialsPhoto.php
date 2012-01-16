<figure>
	<?php echo YsaHtml::link($entry->specials(), $entry->specialsUrl(), array('class' => 'image')); ?>
	<?php echo YsaHtml::link('x', array('deleteSpecials'), array('class' => 'delete')); ?>
</figure>