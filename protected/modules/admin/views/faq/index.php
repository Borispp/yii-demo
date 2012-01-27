<div class="g12">
	<?php $this->beginWidget('YsaAdminForm', array(
		'id'=>'membership-form',
		'action'=>Yii::app()->createUrl('/admin/faq/delete'),
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
			<th class="l">Question</th>
			<th class="w_20">State</th>
			<th class="w_10">&nbsp;</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($entries as $entry) : ?>
		<tr>
			<td><input type="checkbox" class="del" value="<?php echo $entry->id; ?>" name="ids[]" /></td>
			<td class="l">
				<?php echo CHtml::link($entry->question, array('edit', 'id' => $entry->id)); ?>
			</td>
			<td><?php echo $entry->state() ?></td>
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