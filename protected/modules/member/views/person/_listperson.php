<li id="member-person-<?php echo $entry->id?>" class="cf">
	<figure>
		<?php echo $entry->photo(); ?>
	</figure>
	<strong><?php echo $entry->name; ?></strong>
	<span class="menu">
		<?php echo YsaHtml::link('View', array('person/view/' . $entry->id), array('class' => 'icon i_aperture', 'title' => 'View Shooter')); ?>
		<?php echo YsaHtml::link('Edit', array('person/edit/' . $entry->id), array('class' => 'icon i_brush', 'title' => 'Edit Shooter Details')); ?>
		<?php echo YsaHtml::link('Delete', array('person/delete/' . $entry->id), array('class' => 'del icon i_x_alt')); ?>
	</span>
</li>