<?php echo YsaHtml::pageHeaderTitle('Notifications'); ?>
<section class="body w" id="inbox-block">
	<p class="r"><?php echo YsaHtml::link('Add new notification', array('notification/new/'), array('class' => 'btn')); ?></p>
	<?php $this->widget('YsaSearchBar', array(
		'searchOptions' => $searchOptions,
	));?>
	<?php if (count($entries)):?>
	<table class="data">
		<thead>
		<tr>
			<th>ID</th>
			<th>Event Name</th>
			<th>Text</th>
			<th>Date</th>
			<th>State</th>
			<th>Delete</th>
		</tr>
		</thead>
		<tbody>
			<?php foreach ($entries as $entry) : ?>
		<tr>
			<td><?php echo $entry->id?></td>
			<td><?php echo $entry->event->name?></td>
			<td><?php echo nl2br($entry->message)?></td>
			<td><?php echo date('m.d.Y H:i', strtotime($entry->created)) ?></td>
			<td><?php echo $entry->sent ? 'Sent' : 'Unsent'?></td>
			<td>
				<?php if (!$entry->sent):?>
				<?php echo CHtml::link('Delete', array('notification/delete/' . $entry->id)); ?>
				<?php endif?>
			</td>
		</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<? $this->widget('YsaAdminPager',array('pages'=>$pagination)) ?>
	<?php else:?>
	<h3>No notificatons found</h3>
	<?php endif?>
</section>