<section class="body w">
	<table>
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
			<th>Password</th>
			<td><?php echo $entry->password?></td>
		</tr>
		<tr>
			<th>State</th>
			<td><?php echo $entry->state()?></td>
		</tr>
		<tr>
			<th>Description</th>
			<td><?php echo nl2br($entry->description)?></td>
		</tr>
		<tr>
			<th>Added with</th>
			<td><?php echo $entry->getAddedWith()?></td>
		</tr>
		<tr>
			<th>Register Date</th>
			<td><?php echo date('m.d.Y H:i', strtotime($entry->created))?></td>
		</tr>
		<?php if ($entry->updated):?>
		<tr>
			<th>Update Date</th>
			<td><?php echo date('m.d.Y H:i', strtotime($entry->updated))?></td>
		</tr>
		<?php endif?>
	</table>
</section>