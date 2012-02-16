<div class="g12">
	<p>
		<?php echo CHtml::link('Add New', array('add'), array('class' => 'btn i_plus icon ysa fr')); ?>
		<span class="clearfix"></span>
	</p>
	<table class="data">
		<thead>
		<tr>
			<th class="w_1">ID</th>
			<th class="l">Member</th>
			<th class="l">Type</th>
			<th class="w_5">State</th>
			<th class="w_5">Summ</th>
			<th class="w_5">Creation Date</th>
			<th class="w_10">&nbsp;</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($entries as $entry) : ?>
		<tr>
			<td><?php echo $entry->id; ?></td>
			<td class="l">
				<?php echo CHtml::link($entry->getMember()->name(), array('view', 'id' => $entry->id)); ?>
			</td>
			<td class="l">
				<?php echo CHtml::link($entry->type, array('view', 'id' => $entry->id)); ?>
			</td>
			<td><?php echo $entry->state()?></td>
			<td><?php echo $entry->summ?> <?php echo Yii::app()->settings->get('paypal_currency')?></td>
			<td><?php echo date('m.d.Y', strtotime($entry->created))?></td>
			<td>
				<?php echo CHtml::link('View', array('view', 'id' => $entry->id), array('class' => 'btn small blue')); ?>
			</td>
		</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php $this->widget('YsaAdminPager',array('pages'=>$pagination)) ?>
	<div class="clearfix"></div>
</div>