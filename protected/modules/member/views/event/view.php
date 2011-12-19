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

<h2>Albums</h2>
<?php if (count($entry->albums())) : ?>
	<ul>
		<?php foreach ($entry->albums() as $album) : ?>
			<li>
				<?php echo $album->preview(); ?>
				
				<?php echo YsaHtml::link('View', array('album/view/' . $album->id)); ?>
				<?php echo YsaHtml::link('Edit', array('album/edit/' . $album->id)); ?>
				<?php echo YsaHtml::link('Delete', array('album/delete/' . $album->id)); ?>
			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>