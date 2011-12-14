<?php echo CHtml::link('Create New Event', array('create')); ?>

<table class="data">
    <thead>
        <tr>
            <th>Credentials</th>
            <th>Name</th>
            <th>Date</th>
            <th>Type</th>
            <th>State</th>
            <th>Description</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($entries as $entry) : ?>
            <tr>
                <td>
                    ID <?php echo $entry->id; ?><br/>
                    Pass <?php echo $entry->passwd; ?>
                </td>
                <td><?php echo $entry->name; ?></td>
                <td><?php echo $entry->date; ?></td>
                <td><?php echo $entry->type(); ?></td>
                <td><?php echo $entry->state(); ?></td>
                <td><?php echo $entry->description; ?></td>
                <td>
                    <?php echo CHtml::link('View', array('event/view/' . $entry->id), array()); ?><br />
                    <?php echo CHtml::link('Edit', array('event/edit/' . $entry->id), array()); ?><br />
                        <?php if ($entry->type == Event::TYPE_PUBLIC) : ?>
                            <?php echo CHtml::link('Add Album', array('album/create/event/' . $entry->id), array()); ?><br />
                        <?php endif; ?>
                    <?php echo CHtml::link('Delete', array('event/delete/' . $entry->id), array()); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>