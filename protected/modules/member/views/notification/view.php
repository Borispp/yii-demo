<section class="body w">
	<table>
		<tr>
			<th>Message</th>
			<td><?php echo nl2br($entry->message)?></td>
		</tr>
		<tr>
			<th>Created</th>
			<td><?php echo date('m.d.Y H:i', strtotime($entry->created))?></td>
		</tr>
		<?php if ($entry->event):?>
		<tr>
			<th>Events Report</th>
			<td>
				<ol>
				<?php foreach($entry->event as $obEvent):?>
					<li><?php echo $obEvent->name?></li>
				<?php endforeach?>
				</ol>
			</td>
		</tr>
		<?php endif?>
		<?php if ($entry->client):?>
		<tr>
			<th>Show to clients</th>
			<td>
				<ol>
				<?php foreach($entry->client as $obClient):?>
					<li><?php echo $obClient->name?></li>
				<?php endforeach?>
				</ol>
			</td>
		</tr>
		<?php endif?>
		<tr>
			<th>Report</th>
			<td>
				<ol>
				<?php foreach($entry->state as $obAppNotificationState):?>
					<li><?php echo $obAppNotificationState->device_id?> — <?php echo date('m.d.Y H:i', strtotime($obAppNotificationState->created))?></li>
				<?php endforeach?>
				</ol>
			</td>
		</tr>
	</table>
</section>