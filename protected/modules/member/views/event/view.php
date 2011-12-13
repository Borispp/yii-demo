<h2><?php echo $entry->name; ?></h2>

<p><?php echo $entry->description; ?></p>

<p>
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