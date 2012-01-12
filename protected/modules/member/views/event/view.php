<section id="event" data-eventid="<?php echo $entry->id; ?>" class="w">
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


	<?php if (!$entry->isProofing()) : ?>
		<p><?php echo CHtml::link('Create New Event Album', array('album/create/event/' . $entry->id)); ?></p>
	<?php endif; ?>

	<?php if ($entry->isProofing()) : ?>
		<h2>Proofing Album</h2>
	<?php else:?>
		<h2>Albums</h2>
	<?php endif; ?>
	<?php if ($entry->albums) : ?>
		<ul id="event-albums" class="albums cf">
			<?php foreach ($entry->albums as $album) : ?>
				<li id="event-album-<?php echo $album->id?>">
					<figure><?php echo $album->preview(); ?></figure>
					<div class="menu">
						<?php echo YsaHtml::link('View', array('album/view/' . $album->id), array('class' => 'view')); ?>
						<?php echo YsaHtml::link('Edit', array('album/edit/' . $album->id), array('class' => 'edit')); ?>
						<?php if ($entry->isPublic()) : ?>
							<?php echo YsaHtml::link('Delete', array('album/delete/' . $album->id), array('class' => 'delete', 'rel' => $album->id)); ?>
						<?php endif; ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</section>