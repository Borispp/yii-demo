<?php $form=$this->beginWidget('YsaAdminForm', array(
	'id'=>'application-moderate-toolbar-form',
	'action' => array('application/review/id/' . $entry->id . '/'),
)); ?>
<fieldset>
	<label>State</label>
	<section>
		<label>Locked</label>
		<div>
			<strong><?php echo $entry->locked() ? 'Yes' : 'No'; ?></strong>
			<?php echo YsaHtml::link(($entry->locked() ? 'Unlock' : 'Lock'), array('application/toggleLock/id/' . $entry->id . '/'), array('class' => 'btn small yellow icon i_key')); ?>
		</div>
	</section>
	<section>
		<label>Filled</label>
		<div>
			<strong><?php echo $entry->filled() ? 'Yes' : 'No'; ?></strong>
		</div>
	</section>
	<section>
		<label>Paid</label>
		<div>
			<strong><?php echo $entry->isPaid() ? 'Yes' : 'No'; ?></strong>
		</div>
	</section>
	<section>
		<label>Submitted</label>
		<div>
			<strong><?php echo $entry->submitted() ? 'Yes' : 'No'; ?></strong>
		</div>
	</section>
	<section>
		<label>Approved</label>
		<div>
			<strong><?php echo $entry->approved() ? 'Yes' : 'No'; ?></strong>
		</div>
	</section>
	<section>
		<label>Sent to AppStore</label>
		<div>
			<strong><?php echo $entry->isReady() ? 'Yes' : 'No'; ?></strong>
		</div>
	</section>
	<section>
		<label>Approved by AppStore</label>
		<div>
			<strong><?php echo $entry->running() ? 'Yes' : 'No'; ?></strong>
		</div>
	</section>
	<section>
		<label>Rejected by AppStore</label>
		<div>
			<strong><?php echo $entry->rejected() ? 'Yes' : 'No'; ?></strong>
		</div>
	</section>
</fieldset>
<fieldset>
	<label>Moderator Toolbar</label>
	<?php if ($entry->submitted()) : ?>
		<?php if ($entry->running()) : ?>
			<div class="g4">
				<h3>Application is running.</h3>
				<p>
					<?php echo YsaHtml::link('Start all over', array('application/setState/id/' . $entry->id . '/mark/restart/'), array('class' => 'btn ysa small')); ?>
				</p>
			</div>
			<div class="g4">
				<h3>AppStore Link</h3>
				<?php if ($entry->option('appstore_link')) : ?>
					<p><?php echo YsaHtml::link('View in AppStore', $entry->option('appstore_link'), array('class' => 'btn small blue')); ?></p>
				<?php endif; ?>
				<p><input id="application-appstore-link" type="text" value="<?php echo $entry->option('appstore_link')?>" name="appstore_link" /></p>
				<p><?php echo YsaHtml::checkBox('notify_member', true); ?> Notify Memeber</p>
				<p><button id="application-appstore-link-button" class="green">Save</button></p>
			</div>
			
	    <?php elseif ($entry->rejected()) : ?>
			<p class="msg">
				<strong>Application was rejected by AppStore.</strong><br/>
				<?php echo YsaHtml::link('Start all over', array('application/setState/id/' . $entry->id . '/mark/restart/'), array('class' => 'btn red')); ?>
				<?php echo YsaHtml::link('Re-Send to AppStore', array('application/setState/id/' . $entry->id . '/mark/ready/'), array('class' => 'btn ysa big')); ?>
			</p>
		<?php elseif ($entry->isReady()) : ?>
			<p class="msg">
				
				
				<?php echo YsaHtml::link('Rejected', array('application/setState/id/' . $entry->id . '/mark/rejected/'), array('class' => 'btn red')); ?>
				<?php echo YsaHtml::link('Approved', array('application/setState/id/' . $entry->id . '/mark/run/'), array('class' => 'btn ysa big')); ?>
			</p>
		<?php elseif ($entry->approved()) : ?>
			<p class="msg">
				<?php echo YsaHtml::link('Unapprove', array('application/setState/id/' . $entry->id . '/mark/unapprove/'), array('class' => 'btn red')); ?>
				<?php echo YsaHtml::link('Send to AppStore', array('application/setState/id/' . $entry->id . '/mark/ready/'), array('class' => 'btn ysa big')); ?>
			</p>
		<?php elseif ($entry->unapproved() || !$entry->approved()): ?>
			<div class="g2">
				<?php if (count($entry->user->open_tickets)) : ?>
					<p class="error">You cannot approve application with open tickets.</p>
				<?php else:?>
					<?php echo YsaHtml::link('Approve', array('application/setState/id/' . $entry->id . '/mark/approve/'), array('class' => 'btn green', 'id' => 'application-approve-button')); ?>
				<?php endif; ?>
			</div>
			<div class="g4">
				<?php if (!count($entry->user->open_tickets)) : ?>
					<p><textarea id="application-moderate-block-textarea" name="message" cols="30" rows="5" required></textarea></p>
					<p><button id="application-moderate-block-button" class="red">Block &amp; Write Review</button></p>
				<?php endif;?>
			</div>
			<div class="g4">
				<h3>Active Tickets</h3>
				<?php if (count($entry->user->open_tickets)) : ?>
					<ul>
						<?php foreach ($entry->user->open_tickets as $ticket) : ?>
							<li>
								<?php echo YsaHtml::link($ticket->title . ' from ' . $ticket->created('medium', 'short'), array('ticket/view/id/' . $ticket->id)); ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php else:?>	
					<p><strong>No Active Tickets</strong></p>
				<?php endif; ?>
			</div>

		<?php else: ?>

		<?php endif; ?>

	<?php else:?>
		<p class="msg">Application is not submitted yet.</p>
	<?php endif; ?>













<?/*



	<?php if (Application::STATE_CREATED == $entry->state) : ?>

		<p class="msg">Application is not filled.</p>

	<?php elseif (Application::STATE_FILLED == $entry->state) :?>
		<div class="g2">
			<?php if (count($entry->user->open_tickets)) : ?>
				<p class="error">You cannot approve application with open tickets.</p>
			<?php else:?>
				<?php echo YsaHtml::link('Approve', array('application/setState/id/' . $entry->id . '/state/' . Application::STATE_APPROVED . '/'), array('class' => 'btn green', 'id' => 'application-approve-button')); ?>
			<?php endif; ?>
		</div>
		<div class="g4">
			<?php if (!count($entry->user->open_tickets)) : ?>
				<p><textarea id="application-moderate-block-textarea" name="message" cols="30" rows="5" required></textarea></p>
				<p><button id="application-moderate-block-button" class="red">Block &amp; Write Review</button></p>
			<?php endif;?>
		</div>
		<div class="g4">
			<h3>Active Tickets</h3>
			<?php if (count($entry->user->open_tickets)) : ?>
				<ul>
					<?php foreach ($entry->user->open_tickets as $ticket) : ?>
						<li>
							<?php echo YsaHtml::link($ticket->title . ' from ' . $ticket->created('medium', 'short'), array('ticket/view/id/' . $ticket->id)); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php else:?>	
				<p><strong>No Active Tickets</strong></p>
			<?php endif; ?>
		</div>
	<?php elseif (Application::STATE_APPROVED == $entry->state) :?>
		<p class="msg">
			<?php echo YsaHtml::link('Revert to Unapproved', array('application/setState/id/' . $entry->id . '/state/' . Application::STATE_FILLED . '/'), array('class' => 'btn red')); ?>
			<?php echo YsaHtml::link('Send to AppStore', array('application/setState/id/' . $entry->id . '/state/' . Application::STATE_WAITING_APPROVAL . '/'), array('class' => 'btn ysa')); ?>
		</p>
	<?php elseif (Application::STATE_WAITING_APPROVAL == $entry->state) :?>

		<p class="msg">
			<?php echo YsaHtml::link('Rejected by AppStore', array('application/setState/id/' . $entry->id . '/state/' . Application::STATE_REJECTED . '/'), array('class' => 'btn red')); ?>
			<?php echo YsaHtml::link('Approved by AppStore', array('application/setState/id/' . $entry->id . '/state/' . Application::STATE_READY . '/'), array('class' => 'btn ysa')); ?>
		</p>	
	<?php elseif (Application::STATE_UNAPROVED == $entry->state) :?>
		<div class="g2">
			<?php if (count($entry->user->open_tickets)) : ?>
				<p class="error">You cannot approve application with open tickets.</p>
			<?php else:?>
				<?php echo YsaHtml::link('Approve', array('application/setState/id/' . $entry->id . '/state/' . Application::STATE_APPROVED . '/'), array('class' => 'btn green', 'id' => 'application-approve-button')); ?>
			<?php endif; ?>
		</div>
		<div class="g4">
			<h3>Active Tickets</h3>
			<?php if (count($entry->user->open_tickets)) : ?>
				<ul>
					<?php foreach ($entry->user->open_tickets as $ticket) : ?>
						<li>
							<?php echo YsaHtml::link($ticket->title . ' from ' . $ticket->created('medium', 'short'), array('ticket/view/id/' . $ticket->id)); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php else:?>	
				<p><strong>No Active Tickets</strong></p>
			<?php endif; ?>
		</div>
	<?php elseif (Application::STATE_REJECTED == $entry->state) :?>
		<p class="msg">
			<?php echo YsaHtml::link('Revert to Unapproved', array('application/setState/id/' . $entry->id . '/state/' . Application::STATE_FILLED . '/'), array('class' => 'btn red')); ?>
			<?php echo YsaHtml::link('Send to iTunes Store', array('application/setState/id/' . $entry->id . '/state/' . Application::STATE_WAITING_APPROVAL . '/'), array('class' => 'btn ysa')); ?>
		</p>

	<?php elseif (Application::STATE_READY == $entry->state) :?>

		<div class="g4">
			<h3>AppStore Link</h3>

			<?php if ($entry->option('appstore_link')) : ?>
				<p><?php echo YsaHtml::link('View in AppStore', $entry->option('appstore_link'), array('class' => 'btn small blue')); ?></p>
			<?php endif; ?>
			<p><input id="application-appstore-link" type="text" value="<?php echo $entry->option('appstore_link')?>" name="appstore_link" /></p>
			<p><?php echo YsaHtml::checkBox('notify_member', true); ?> Notify Memeber</p>
			<p><button id="application-appstore-link-button" class="green">Save</button></p>

		</div>
		<div class="g4">
			<?php echo YsaHtml::link('Revert to Unapproved', array('application/setState/id/' . $entry->id . '/state/' . Application::STATE_FILLED . '/'), array('class' => 'btn red')); ?>
		</div>

	<?php endif; ?>
*/?>
</fieldset>
<?php $this->endWidget(); ?>
