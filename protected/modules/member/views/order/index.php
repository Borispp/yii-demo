<?php echo YsaHtml::pageHeaderTitle('Orders'); ?>
<section class="body w" id="inbox-block">
	<?php if (count($entries)):?>
	<table class="data">
		<thead>
		<tr>
			<th>Name</th>
			<th>Email</th>
			<th>Date</th>
			<th>Link to PDF</th>
		</tr>
		</thead>
		<tbody>
			<?php foreach ($entries as $entry) : ?>
		<tr>
			<td>
				<?php echo $entry->last_name?>
			</td>
			<td>
				<?php echo $entry->email?>
			</td>
			<td><?php echo date('m.d.Y H:i', strtotime($entry->created)) ?></td>
			<td><?php echo CHtml::link('PDF', array('showpdf', 'id' => $entry->id), array('target' => '_blank')); ?></td>
		</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<? $this->widget('YsaAdminPager',array('pages'=>$pagination)) ?>
	<?php else:?>
	<h3>No messages yet</h3>
	<?php endif?>
</section>