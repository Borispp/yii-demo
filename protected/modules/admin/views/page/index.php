<div class="g12">
    <?php $this->beginWidget('YsaAdminForm', array(
        'id'=>'page-form',
        'action'=>Yii::app()->createUrl('/admin/page/delete'),
        'method'=>'post',
    )); ?>
    
    <p>
        <?php echo YsaHtml::link('Add New', array('add'), array('class' => 'btn i_plus icon ysa fr')); ?>
        <span class="clearfix"></span>
    </p>
    
    <table class="data">
        <thead>
            <tr>
                <th class="w_1"><input type="checkbox" value="" class="ids-toggle" /></th>
				<th class="w_5">Type</th>
                <th class="l">Title</th>
                <th class="w_5">Status</th>
                <th class="w_10">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($entries as $entry) : ?>
                <tr>
                    <td>
						<?php if ($entry->type == Page::TYPE_GENERAL) : ?>
							<input type="checkbox" class="del" value="<?php echo $entry->id; ?>" name="ids[]" />
						<?php else:?>
							&nbsp;
						<?php endif; ?>
					</td>
					<td class="page-<?php echo strtolower($entry->type()); ?>"><strong><?php echo $entry->type(); ?></strong></td>
                    <td class="l">
                        <h5><?php echo YsaHtml::link($entry->title, array('edit', 'id' => $entry->id)); ?></h5>
						<div>
							Slug: <strong><?php echo $entry->slug; ?></strong>
						</div>
                    </td>
                    <td class="<?php echo strtolower($entry->state()); ?>">
                        <strong><?php echo $entry->state(); ?></strong>
                    </td>
                    <td>
                        <?php echo YsaHtml::link('Edit', array('edit', 'id' => $entry->id), array('class' => 'btn small blue')); ?>
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