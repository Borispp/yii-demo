<li id="studio-person-<?php echo $entry->id?>" class="cf">
	<figure>
		<?php echo $entry->photo(); ?>
	</figure>
	<?php echo YsaHtml::link('<strong>' . $entry->name . '</strong>', array('person/view/' . $entry->id), array('class' => 'view')); ?>
	<span class="menu">
		<?php echo YsaHtml::link('Edit', array('person/edit/' . $entry->id), array('class' => 'icon i_pencil', 'title' => 'Edit Shooter Details')); ?>
		<?php echo YsaHtml::link('Delete', array('person/delete/' . $entry->id), array('class' => 'del icon i_delete', 'title' => 'Delete Shooter')); ?>
	</span>
	<span class="sort">
		<?php echo YsaHtml::link('Sort', '#', array('class' => 'move icon i_cursor_drag_arrow')); ?>
	</span>
</li>