<h2>clients list</h2>

<?php echo YsaHtml::link('Add a New Client', array('create')); ?>

<table class="data">
    <thead>
        <tr>
            <th><input type="checkbox" value="" class="ids-toggle" /></th>
            <th>Name</th>
            <th>Key</th>
            <th>Status</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($entries as $entry) : ?>
            <tr>
                <td><input type="checkbox" class="del" value="<?php echo $entry->id; ?>" name="ids[]" /></td>
                <td>
                    <h4><?php echo CHtml::link($entry->name(), array('view', 'id' => $entry->id)); ?></h4>
                    <span><?php echo $entry->email; ?></span>
                </td>
                <td><?php echo $entry->passwd; ?></td>
                <td><?php echo $entry->state(); ?></td>
                <td>
                    <?php echo CHtml::link('View', array('view', 'id' => $entry->id)); ?>
                    <br/>
                    <?php echo CHtml::link('Edit', array('edit', 'id' => $entry->id)); ?>
                    <br/>
                    <?php echo CHtml::link('Activate', array('activate', 'id' => $entry->id)); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php $this->widget('YsaMemberPager',array('pages'=>$pagination)) ?>