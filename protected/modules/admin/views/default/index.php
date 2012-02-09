<div class="g6 widgets">
		
	<div class="widget" data-icon="calendar">
		<h3 class="handle">Latest Applications</h3>
		<div>
		<table class="data">
			<thead>
				<tr>
					<th class="w_1">ID</th>
					<th class="l">Name</th>
					<th class="w_5">Status</th>
					<th class="w_40">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($applications as $entry) : ?>
					<tr>
						<td><?php echo $entry->id; ?></td>
						<td class="l">
							<?php echo YsaHtml::link($entry->name, array('edit', 'id' => $entry->id)); ?>
						</td>
						<td class="state <?php echo strtolower($entry->status())?>"><?php echo $entry->status(); ?></td>
						<td>
							<?php echo YsaHtml::link('Moderate', array('application/moderate', 'id' => $entry->id), array('class' => 'btn small green')); ?>
							<?php echo YsaHtml::link('Edit', array('application/edit', 'id' => $entry->id), array('class' => 'btn small blue')); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>	
		</div>
	</div>
	
</div>