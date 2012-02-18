<div class="g6 widgets">

	<div class="widget ui-sortable" data-icon="application" >
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
				<?php if (count($applications)) : ?>
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
				<?php endif; ?>
			</tbody>
		</table>	
		</div>
	</div>
	
	<div class="widget number-widget ui-sortable" id="widget_number">
		<h3 class="handle">Numbers<a class="collapse" title="collapse widget"></a></h3>
		<div>
			<ul>
				<?php foreach($totals as $total) : ?>
					<li><a href=""><span><?php echo $total['count'] ?></span> <?php echo $total['title'] ?></a></li>
				<?php endforeach ?>
			</ul>
		</div>
	</div>
	
</div>	
<div class="g6 widgets">
	
	<div class="widget ui-sortable" data-icon="companies" >
		<h3 class="handle">Contacts Messages<a class="collapse" title="collapse widget"></a></h3>
		<div>
		<table class="data">
			<thead>
				<tr>
					<th class="w_10">Created</th>
					<th class="l w_20">Sender</th>
					<th class="l">Message</th>
				</tr>
			</thead>
			<tbody>
				<?php if (count($c_messages)) : ?>
				<?php foreach ($c_messages as $entry) : ?>
					<tr>
						<td>
							<?php echo $entry->created('medium', 'short'); ?>
						</td>
						<td class="l">
							<strong><?php echo $entry->name; ?></strong>
							<br/>
							<?php echo YsaHtml::mailto($entry->email, $entry->email); ?>
						</td>
						<td class="l">
							<?php echo substr($entry->message(), 0, 256); ?>
						</td>
					</tr>
				<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
		</div>
		
	</div>
	
	<div class="widget ui-sortable" data-icon="paypal" >
		<h3 class="handle">Payments<a class="collapse" title="collapse widget"></a></h3>
		<div>
			
			<table class="data">
				<thead>
				<tr>
					<th class="w_1">ID</th>
					<th class="l">Member</th>
					<th class="l">Type</th>
					<th class="w_5">State</th>
					<th class="w_5">Summ</th>
					<th class="w_6">Creation Date</th>
					<th class="w_10">&nbsp;</th>
				</tr>
				</thead>
				<tbody>
					<?php if (count($payments)) : ?>
					<?php foreach ($payments as $entry) : ?>
					<tr>
						<td><?php echo $entry->id; ?></td>
						<td class="l">
							<?php echo CHtml::link($entry->getMember()->name(), array('payment/view', 'id' => $entry->id)); ?>
						</td>
						<td class="l">
							<?php echo CHtml::link($entry->type, array('payment/view', 'id' => $entry->id)); ?>
						</td>
						<td><?php echo $entry->state()?></td>
						<td><?php echo $entry->summ?> <?php echo Yii::app()->settings->get('paypal_currency')?></td>
						<td><?php echo $entry->created('medium', 'short') ?></td>
						<td>
							<?php echo CHtml::link('View', array('payment/view', 'id' => $entry->id), array('class' => 'btn small blue')); ?>
						</td>
					</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
			
		</div>
	</div>
	
</div>