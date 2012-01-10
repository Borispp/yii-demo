<div class="w">
	<p><?php echo YsaHtml::link('Notifications', array('notification/')); ?></p>
	<?php $this->widget('YsaSearchBar', array(
		'searchOptions' => $searchOptions,
	));?>
	<p class="r"><?php echo YsaHtml::link('Register New Client', array('add'), array('class' => 'btn')); ?></p>
	<table class="data">
		<thead>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Email</th>
				<th>Phone</th>
				<th>Description</th>
				<th>Created</th>
				<th>Updated</th>
				<th>State</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($entries as $entry) : ?>
				<tr>
					<td><?php echo $entry->id;?></td>
					<td><?php echo $entry->name?></td>
					<td><?php echo $entry->email?></td>
					<td><?php echo $entry->phone?></td>
					<td><?php echo $entry->description?></td>
					<td><?php echo $entry->created?></td>
					<td><?php echo $entry->updated?></td>
					<td><?php echo $entry->state()?></td>
					<td>
						<?php echo YsaHtml::link('View', array('client/view/' . $entry->id), array()); ?><br />
						<?php echo YsaHtml::link('Edit', array('client/edit/' . $entry->id), array()); ?><br />
						<?php echo YsaHtml::link('Delete', array('client/delete/' . $entry->id), array()); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<? $this->widget('YsaAdminPager',array('pages'=>$pagination)) ?>
</div>