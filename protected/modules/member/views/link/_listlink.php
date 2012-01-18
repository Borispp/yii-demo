<li id="member-link-<?php echo $entry->id?>" class="cf">
	
	<figure></figure>
	<strong><?php echo $entry->name; ?></strong>
	<?php echo YsaHtml::link($entry->url, $entry->url, array('rel' => 'external', 'class' => 'url')); ?>
	
	<span class="menu">
		<?php echo YsaHtml::link('Edit', array('link/edit/' . $entry->id), array('class' => 'icon i_brush', 'title' => 'Edit Link Details')); ?>
		<?php echo YsaHtml::link('Delete', array('link/delete/' . $entry->id), array('class' => 'del icon i_x_alt', 'title' => 'Delete Link')); ?>
	</span>
</li>