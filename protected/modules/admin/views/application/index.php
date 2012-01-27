<div class="g12">
	<?php $this->beginWidget('YsaAdminForm', array(
		'id'=>'application-form',
		'action'=>Yii::app()->createUrl('/admin/application/delete'),
		'method'=>'post',
	)); ?>
	<table class="data">
		<thead>
			<tr>
				<th class="w_1">ID</th>
				<th class="l">Name</th>
				<th class="w_5">Status</th>
				<th class="w_20">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($entries as $entry) : ?>
				<tr>
					<td><?php echo $entry->id; ?></td>
					<td class="l">
						<?php echo YsaHtml::link($entry->name, array('edit', 'id' => $entry->id)); ?>
					</td>
					<td class="state <?php echo strtolower($entry->status())?>"><?php echo $entry->status(); ?></td>
					<td>
						<?php echo YsaHtml::link('Moderate', array('moderate', 'id' => $entry->id), array('class' => 'btn small green')); ?>
						<?php echo YsaHtml::link('Edit', array('edit', 'id' => $entry->id), array('class' => 'btn small blue')); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	
	<?php $this->widget('YsaAdminPager',array('pages'=>$pagination)) ?>
	<div class="clearfix"></div>
	
	<?php $this->endWidget(); ?>
</div>