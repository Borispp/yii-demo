<section id="event" eventid="<?php echo $entry->id; ?>">
	<?php echo YsaHtml::pageHeaderTitle($entry->name); ?>

	<p><?php echo CHtml::link('Edit Event', array('event/edit/' . $entry->id)); ?></p>

	<p><?php echo $entry->description; ?></p>

	<p>
		Credentials : ID - <?php echo $entry->id; ?>, Password: <?php echo $entry->passwd; ?>
		<br/>
		<?php echo $entry->state(); ?>
		<br/>
		<?php echo $entry->type(); ?>
		<br />
		date: <?php echo $entry->date; ?>
	</p>


	<?php if (Event::TYPE_PUBLIC == $entry->type) : ?>
		<p><?php echo CHtml::link('Create New Event Album', array('album/create/event/' . $entry->id)); ?></p>
	<?php endif; ?>

	<?php if (Event::TYPE_PROOF == $entry->type) : ?>
		<h2>Proofing Album</h2>
	<?php else:?>
		<h2>Albums</h2>
	<?php endif; ?>
	<?php if (count($entry->albums())) : ?>
		<ul id="event-albums">
			<?php foreach ($entry->albums() as $album) : ?>
				<li id="event-album-<?php echo $album->id?>">
					<?php echo $album->preview(); ?>
					<?php echo YsaHtml::link('View', array('album/view/' . $album->id), array('class' => 'view')); ?>
					<?php echo YsaHtml::link('Edit', array('album/edit/' . $album->id), array('class' => 'edit')); ?>
					<?php echo YsaHtml::link('x', array('album/delete/' . $album->id), array('class' => 'delete', 'rel' => $album->id)); ?>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</section>