<li id="studio-link-<?php echo $entry->id?>" class="cf">	
	<figure class="<?php echo $folder?>"><?php echo $entry->icon($folder); ?></figure>
	<strong><?php echo $entry->name; ?></strong>
	<?php echo YsaHtml::link($entry->url, $entry->url, array('rel' => 'external', 'class' => 'url')); ?>
	<span class="menu">
		<?php echo YsaHtml::link('Edit', array('link/editCustom/' . $entry->id), array('class' => 'icon i_pencil', 'title' => 'Edit Link Details')); ?>
		<?php echo YsaHtml::link('Delete', array('link/deleteCustom/' . $entry->id), array('class' => 'del icon i_delete', 'title' => 'Delete Link')); ?>
	</span>
	<span class="sort">
		<?php echo YsaHtml::link('Sort', '#', array('class' => 'move icon i_cursor_drag_arrow')); ?>
	</span>
</li>