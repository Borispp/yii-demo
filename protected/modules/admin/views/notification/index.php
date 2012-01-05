<div class="g12">
	<?php $this->beginWidget('YsaAdminForm', array(
		'id'=>'notification-form',
		'action'=>Yii::app()->createUrl('/admin/notification/delete'),
		'method'=>'post',
	)); ?>

	<p>
		<?php echo CHtml::link('Add New', array('add'), array('class' => 'btn i_plus icon ysa fr')); ?>
		<span class="clearfix"></span>
	</p>

	<table class="data">
		<thead>
		<tr>
			<th class="w_1"><input type="checkbox" value="" class="ids-toggle" /></th>
			<th class="l">Title</th>
			<th class="w_20">Message</th>
			<th class="w_20">Created</th>
			<th class="w_10">&nbsp;</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($entries as $entry) : ?>
		<tr>
			<td><input type="checkbox" class="del" value="<?php echo $entry->id; ?>" name="ids[]" /></td>
			<?php /*<td >
				<?php echo CHtml::link($entry->code, array('edit', 'id' => $entry->id)); ?>
			</td>*/?>
			<td class="l"><?php echo $entry->title?></td>
			<td><?php echo $entry->message?></td>
			<td><?php echo $entry->created?></td>
			<td>
				<?php echo CHtml::link('View', array('view', 'id' => $entry->id), array('class' => 'btn small blue')); ?>
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