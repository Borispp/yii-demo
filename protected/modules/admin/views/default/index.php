<div class="g6 widgets">	
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
							<?php echo YsaHelpers::truncate($entry->message(), 256); ?>
						</td>
					</tr>
				<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
			<p class="c"><?php echo YsaHtml::link('View All Messages', array('contact/'), array('class' => 'btn ysa small')); ?></p>
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
							<?php if (!$entry->isBroken()):?>
							<?php echo CHtml::link($entry->getMember()->name(), array('payment/view', 'id' => $entry->id)); ?>
							<?php else:?>
								Unknown (broken transaction)
							<?php endif;?>
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
			<p class="c"><?php echo YsaHtml::link('View All Payments', array('payment/'), array('class' => 'btn ysa small')); ?></p>
		</div>
	</div>
	
</div>

<div class="g12 widgets">
	<div class="widget ui-sortable" data-icon="application" >
		<h3 class="handle">Latest Applications</h3>
		<div>
		<table class="data">
			<thead>
				<tr>
					<th class="w_1">&nbsp;</th>
					<th class="l">Name</th>
					<th class="w_5">Filled</th>
					<th class="w_5">Paid</th>
					<th class="w_5">Submitted</th>
					<th class="w_5">Approved</th>
					<th class="w_5">Locked</th>
					<th class="w_5">AppStore</th>
					<th class="w_15">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php if (count($applications)) : ?>
					<?php foreach ($applications as $entry) : ?>
						<tr>
							<td>
								<?php echo YsaHtml::link($entry->image('itunes_logo', 50, 50), array('application/moderate', 'id' => $entry->id)); ?>
							</td>
							<td class="l">
								<?php echo YsaHtml::link($entry->name, array('application/moderate', 'id' => $entry->id)); ?>
							</td>
							<td class="<?php echo $entry->filled() ? 'true' : 'false'?>">
								<?php echo $entry->filled() ? '<strong>Yes</strong>' : 'No'; ?>
							</td>
							<td class="<?php echo $entry->isPaid() ? 'true' : 'false'?>">
								<?php echo $entry->isPaid() ? '<strong>Yes</strong>' : 'No'; ?>
							</td>
							<td class="<?php echo $entry->submitted() ? 'true' : 'false'?>">
								<?php echo $entry->submitted() ? '<strong>Yes</strong>' : 'No'; ?>
							</td>
							<td class="<?php echo $entry->approved() ? 'true' : 'false'?>">
								<?php echo $entry->approved() ? '<strong>Yes</strong>' : 'No'; ?>
							</td>
							<td class="<?php echo $entry->locked() ? 'true' : 'false'?>">
								<?php echo $entry->locked() ? '<strong>Yes</strong>' : 'No'; ?>
							</td>
							<td class="<?php echo $entry->rejected() ? 'false' : ($entry->running() ? 'true' : ($entry->isReady() ? 'app-ready' : 'none'))?>">
								<?php if ($entry->rejected()) : ?>
									Rejected
								<?php elseif($entry->running()):?>
									Running
								<?php elseif($entry->isReady()):?>
									<strong>Sent</strong>
								<?php else:?>
									&mdash;
								<?php endif; ?>
							</td>
							<td>
								<?php echo YsaHtml::link('Moderate', array('application/moderate', 'id' => $entry->id), array('class' => 'btn small green')); ?>
								<?php echo YsaHtml::link('Edit', array('application/edit', 'id' => $entry->id), array('class' => 'btn small blue')); ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>	
			<p class="c"><?php echo YsaHtml::link('View All Applications', array('application/'), array('class' => 'btn ysa small')); ?></p>
		</div>
	</div>
</div> 