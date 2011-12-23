<?php echo YsaHtml::pageHeaderTitle($entry->subject); ?>
<section class="body w">
	<p><?php echo CHtml::link('Back to list ', array('inbox/' . $entry->id)); ?></p>
	<table>
		<tr>
			<th>Date</th>
			<td><?php echo date('m.d.Y H:i', strtotime($entry->created))?></td>
		</tr>
		<tr>
			<th>Name</th>
			<td><?php echo $entry->name?></td>
		</tr>
		<tr>
			<th>Email</th>
			<td><?php echo $entry->email?></td>
		</tr>
		<tr>
			<th>Phone</th>
			<td><?php echo $entry->phone?></td>
		</tr>
		<tr>
			<th>Subject</th>
			<td><?php echo $entry->subject?></td>
		</tr>
		<tr>
			<th>Message</th>
			<td><?php echo nl2br($entry->message)?></td>
		</tr>
	</table>
</section>