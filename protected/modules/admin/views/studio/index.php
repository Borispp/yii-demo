<div class="g12">
	<?php $this->beginWidget('YsaAdminForm', array(
		'id'=>'studio-form',
		'action'=>Yii::app()->createUrl('/admin/application/delete'),
		'method'=>'post',
	)); ?>
	<table class="data">
		<thead>
		<tr>
			<th class="w_1">ID</th>
			<th class="l">Owner</th>
			<th class="l">Name</th>
			<th class="w_10">&nbsp;</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($entries as $entry) : ?>
		<tr>
			<td><?php echo $entry->id; ?></td>
			<td class="l">
				<?php echo $entry->user->name()?>
			</td>
			<td class="l">
				<?php echo CHtml::link($entry->name, array('view', 'id' => $entry->id)); ?>
			</td>
			<td>
				<?php echo CHtml::link('View', array('view', 'id' => $entry->id), array('class' => 'btn small blue')); ?>

			</td>
		</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php $this->endWidget(); ?>
	<?php $this->widget('YsaAdminPager',array('pages'=>$pagination)) ?>
</div>