<div class="g12">
	<?php echo $this->renderPartial('/_messages/save');?>
	<?php echo $this->renderPartial('/_messages/error');?>
	<div class="form">
		<?php $form=$this->beginWidget('YsaAdminForm', array(
			'id'=>'application-moderate-form',
		)); ?>
		<?php foreach($options as $section => $properties):?>
			<fieldset>
				<label><?php echo $section?></label>
				<?php foreach($properties as $label => $value):?>
				<section>
					<label><?php echo $label?></label>
					<div><?php echo $value?></div>
				</section>
				<?endforeach?>
			</fieldset>
		<?endforeach?>
		<fieldset>
			<label>Download</label>
			<section>
				<label>&nbsp;</label>
				<div>
					<?php if (!$itunes_logo):?>
						<div class="errorMessage">No iTunes logo uploaded</div>
					<?php endif?>
					<?php if (!$icon):?>
						<div class="errorMessage">No iOS uploaded</div>
					<?php endif?>
					<?php if ($icon):?>
						<?php echo YsaHtml::link('Download iPad Icon', array('application/download/id/' . $entry->id . '/image/icon'), array('class' => 'btn')); ?>
					<?php endif?>
					<?php if ($itunes_logo):?>
						<?php echo YsaHtml::link('Download iTunes Logo', array('application/download/id/' . $entry->id . '/image/itunes_logo'), array('class' => 'btn')); ?>
					<?php endif?>
				</div>
			</section>
		</fieldset>
		<?php $this->endWidget(); ?>
		
		<?php $form=$this->beginWidget('YsaAdminForm', array(
			'id'=>'application-moderate-toolbar-form',
			'action' => array('application/review/id/' . $entry->id . '/'),
		)); ?>
		<fieldset>
			<label>Moderator Toolbar</label>
			
			
			<?php if ($entry->submitted()) : ?>
				
				<?php if ($entry->approved()) : ?>
					
					application aproved
			
				<?php elseif ($entry->unapproved()): ?>
					
					application unapproved
			
				<?php else:?>
					
					
					
					<div class="g2">
						<?php if (count($entry->user->open_tickets)) : ?>
							<p class="error">You cannot approve application with open tickets.</p>
						<?php else:?>
							<?php echo YsaHtml::link('Approve', array('application/setState/id/' . $entry->id . '/mark/approved/'), array('class' => 'btn green', 'id' => 'application-approve-button')); ?>
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
	</div>
</div>
<script type="text/javascript">
	$(function(){
		$('#application-moderate-form a.image').fancybox();
		$('#application-approve-button').click(function(e){
			e.preventDefault();
			var link = $(this);
			$.confirm('Are you sure you want to APPROVE this Application?', function(){
				window.location.href = link.attr('href');
			});
		});
	});
</script>