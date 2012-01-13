<div class="g12">
    <?php $this->beginWidget('YsaAdminForm', array(
        'id'=>'member-form',
        'action'=>Yii::app()->createUrl('/admin/member/delete'),
        'method'=>'post',
    )); ?>
    
    <p>
		<?php echo CHtml::link('Export to CSV', array('member/export'), array('class' => 'btn i_download icon')); ?>
        <?php echo CHtml::link('Add New', array('add'), array('class' => 'btn i_plus icon ysa fr')); ?>
        <span class="clearfix"></span>
    </p>
    
    <table class="data">
        <thead>
            <tr>
                <th class="w_1"><input type="checkbox" value="" class="ids-toggle" /></th>
                <th class="w_1">ID</th>
                <th class="l">Name</th>
                <th class="w_5">Status</th>
                <th class="w_10">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($entries as $entry) : ?>
                <tr>
                    <td><input type="checkbox" class="del" value="<?php echo $entry->id; ?>" name="ids[]" /></td>
                    <td><?php echo $entry->id; ?></td>
                    <td class="l">
                        <h4><?php echo CHtml::link($entry->name(), array('view', 'id' => $entry->id)); ?></h4>
                        <span><?php echo $entry->email; ?></span>
                    </td>
                    <td><?php echo $entry->state(); ?></td>
                    <td>
                        <?php echo CHtml::link('View', array('view', 'id' => $entry->id), array('class' => 'btn small blue')); ?>
                        <?php echo CHtml::link('Edit', array('edit', 'id' => $entry->id), array('class' => 'btn small blue')); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	<p class="fl"><button class="submit small red icon i_recycle delete-entries">Delete Selected</button></p>
	<?php $this->widget('YsaAdminPager',array('pages'=>$pagination)) ?>
	<div class="clearfix"></div>
    <?php $this->endWidget(); ?>
</div>