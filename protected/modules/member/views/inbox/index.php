<?php echo YsaHtml::pageHeaderTitle('Inbox'); ?>
<section class="body w" id="inbox-block">
<?php if (count($entries)):?>
<?php $this->widget('YsaSearchBar', array(
		'searchOptions' => $searchOptions,
	));?>

<table class="data">
	<thead>
	<tr>
		<th>From</th>
		<th>Subject</th>
		<th>Date</th>
		<th>State</th>
		<th>&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($entries as $entry) : ?>
	<tr>
		<td>
			<?php echo $entry->email?>
		</td>
		<td><?php echo CHtml::link($entry->subject, array('inbox/view/' . $entry->id)); ?></td>
		<td><?php echo date('m.d.Y H:i', strtotime($entry->created)) ?></td>
		<td><?php echo $entry->unread ? 'unread' : ''?></td>
		<td>

			<?php echo CHtml::link('Delete', array('inbox/delete/' . $entry->id)); ?>
		</td>
	</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<? $this->widget('YsaAdminPager',array('pages'=>$pagination)) ?>
<?php else:?>
	<h3>No messages yet</h3>
<?php endif?>
</section>