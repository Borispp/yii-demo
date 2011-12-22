<li id="member-link-<?php echo $entry->id?>">
	<span>
		<?php echo $entry->name; ?>
	</span>
	
	<?php echo YsaHtml::link($entry->url, $entry->url, array('rel' => 'external')); ?>
	
	<?php echo YsaHtml::link('edit', array('link/edit/' . $entry->id)); ?>
	
	<?php echo YsaHtml::link('delete', array('link/delete/' . $entry->id)); ?>
</li>