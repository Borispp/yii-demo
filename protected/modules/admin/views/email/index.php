<div class="g12">
    <?php $this->beginWidget('YsaAdminForm', array(
        'id'=>'email-form',
        'action'=>Yii::app()->createUrl('/admin/email/delete'),
        'method'=>'post',
    )); ?>
    
    <p>
        <?php echo CHtml::link('Add New', array('add'), array('class' => 'btn i_plus icon ysa fr')); ?>
        <span class="clearfix"></span>
    </p>
    
    <table class="data">
        <thead>
            <tr>
                <th class="w_1"><input type="checkbox" value="<?php echo $entry->id; ?>" class="ids-toggle" /></th>
                <th class="w_10">Name</th>
                <th class="l w_40">Subject</th>
                <th class="l">Help</th>
                <th class="w_10">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($entries as $entry) : ?>
                <tr>
                    <td><input type="checkbox" class="del" value="<?php echo $entry->id; ?>" name="ids[]" /></td>
                    <td><?php echo $entry->name; ?></td>
                    <td class="l"><?php echo CHtml::link($entry->subject, array('edit', 'id' => $entry->id)); ?></td>
                    <td class="l"><?php echo $entry->help(); ?></td>
                    <td>
                        <?php echo CHtml::link('Edit', array('edit', 'id' => $entry->id), array('class' => 'btn small blue')); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p>
        <button class="submit small red icon i_recycle delete-entries">Delete Selected</button>
    </p>
    <?php $this->endWidget(); ?>
</div>