<div class="g12">
	<div class="g6">
		<h3>Ticket Info</h3>
		
		<dl>
			<dt>ID</dt>
			<dd><?php echo $entry->id; ?></dd>
			
			<dt>Code</dt>
			<dd><?php echo $entry->code; ?></dd>
			
			<dt>State</dt>
			<dd><?php echo $entry->state(); ?>  <?php echo YsaHtml::link(Ticket::STATE_ACTIVE == $entry->state ? 'Close' : 'Open', array('ticket/toggle/id/' . $entry->id . '/'), array('class' => 'btn small'))?> </dd>
			
			<dt>Title</dt>
			<dd><?php echo $entry->title; ?></dd>
			
			<dt>Created</dt>
			<dd><?php echo $entry->created('medium', 'short'); ?></dd>
			
			<dt>Last Update</dt>
			<dd><?php echo $entry->updated('medium', 'short'); ?></dd>
			
			<dt>Member</dt>
			<dd><?php echo YsaHtml::link($entry->user->name(), array('member/view/id/' . $entry->user->id . '/'), array('class' => 'btn small ysa')); ?></dd>
			
			<dt>Application</dt>
			<dd><?php echo YsaHtml::link('Moderate', array('application/moderate/id/' . $entry->user->application->id . '/'), array('class' => 'btn small')); ?></dd>
		</dl>
	</div>
	
	<div class="g6">
		<h3>Replies</h3>
		
		<ul class="replies">
			<?php foreach ($entry->replies as $r) : ?>
				<li class="<?php echo $entry->user->role?>">
					<div class="replier"><strong><?php echo $r->replier->name(); ?></strong></div>
					<div class="info">
						Reply time: <?php echo $r->created('medium', 'short'); ?>
					</div>
					<p class="reply"><?php echo $r->message; ?></p>
				</li>
			<?php endforeach; ?>
		</ul>
		
		<?php if (Ticket::STATE_ACTIVE == $entry->state) : ?>
			<?php $form=$this->beginWidget('YsaAdminForm', array(
				'id'=>'ticket-add-reply-form',
			)); ?>
			<div class="add-reply">
				<p>
					<?php echo $form->textArea($reply,'message', array(
						'data-autogrow' => 'true',
						'rows'          => 5,
					)); ?>
					<?php echo $form->error($reply,'message'); ?>
				</p>

				<p>
					<?php echo $form->checkBox($reply,'notify', array(
						'checked' => $reply->notify,
					)); ?>
					Notify Member
				</p>

				<p>
					<button id="application-moderate-block-button" class="ysa">Add Reply</button>

					<button id="application-moderate-block-button" name="close" value="1" class="green">Add Reply &amp; Close</button>
				</p>
			</div>
			<?php $this->endWidget(); ?>
		<?php else: ?>
			<h4>Ticket is closed. </h4>
		<?php endif; ?>
	</div>
</div>