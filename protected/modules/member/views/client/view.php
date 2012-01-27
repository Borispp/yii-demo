<div class="w" id="client" data-clientid="<?php echo $entry->id; ?>">
	<section class="box">
		<div class="box-title">
			<h3><?php echo $entry->name; ?></h3>
			<div class="box-title-button">
				<?php echo YsaHtml::link('<span class="icon i_bell"></span>Send Push Notification', '/member/notification/new/recipient/'.$entry->id.'/type/client', array('class' => 'secondary iconed', 'id' => 'send-push-link')); ?>
				<?php echo YsaHtml::link('<span class="icon i_pencil"></span>Edit Client', array('client/edit/' . $entry->id . '/'), array('class' => 'secondary iconed')); ?>
			</div>
		</div>
		<div class="box-content cf">
			
			<div class="description shadow-box">
				<?php if ($entry->description) : ?>
					<div class="title">Description</div>
					<p><?php echo $entry->description; ?></p>
				<?php endif; ?>
				
				<div class="title">State</div>
				<p><?php echo YsaHtml::dropDownList('state', $entry->state, $entry->getStates(), array('id' => 'description-state')); ?></p>
				<dl>
					<dt>Registered</dt>
					<dd><?php echo $entry->created('medium', null); ?></dd>

					<?php if ($entry->updated):?>
						<dt>Updated</dt>
						<dd><?php echo $entry->updated('medium', null); ?></dd>					
					<?php endif;?>
				</dl>
			</div>
			
			
			<div class="main-box">
				<div class="main-box-title">
					<h3>Client Information</h3>
					<div class="cf"></div>
				</div>
				<div class="shadow-box">
					<table class="data">
						<tr>
							<th>Name</th>
							<td><strong><?php echo $entry->name?></strong></td>
						</tr>
						<tr>
							<th>Email</th>
							<td><?php echo YsaHtml::mailto($entry->email, $entry->email) ?></td>
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
							<th>Added with</th>
							<td><?php echo $entry->getAddedWith()?></td>
						</tr>
					</table>
				</div>
				<p>&nbsp;</p>
				<div class="main-box-title">
					<h3>Attached Events</h3>
					<div class="cf"></div>
				</div>
				<div class="shadow-box">
					<?php if (count($entry->events)) : ?>
						<ul class="event-list">
							<?php foreach ($entry->events as $event) : ?>
								<li class="cf">
									<?php echo YsaHtml::link($event->preview() . '<span>' . $event->name . '</span>', array('event/view/' . $event->id . '/')); ?>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php else:?>
						<div class="empty-list">Empty List</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
</div>