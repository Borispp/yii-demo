<li id="member-person-<?php echo $entry->id?>">
	<figure>
		<?php echo $entry->photo(); ?>
	</figure>
	<strong>
		<?php echo $entry->name; ?>
	</strong>
	
	<?php echo YsaHtml::link('view', array('person/view/' . $entry->id)); ?>
	<?php echo YsaHtml::link('edit', array('person/edit/' . $entry->id)); ?>
	<?php echo YsaHtml::link('delete', array('person/delete/' . $entry->id)); ?>
</li>