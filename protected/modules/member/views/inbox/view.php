<div class="w" id="inbox-view" data-inboxid="<?php echo $entry->id; ?>">
	<section class="box">
		<div class="box-title">
			<h3><?php echo $entry->subject; ?></h3>
			<?php if ($entry->client):?>
			<div class="box-title-button">
				<?php echo YsaHtml::link('<span class="icon i_spechbubble_sq_line"></span>Respond with Push Notification ', '/member/notification/new/recipient/'.$entry->client->id.'/type/client', array('class' => 'secondary iconed', 'id' => 'send-push-link')); ?>
			</div>
			<?php endif?>
		</div>
		<div class="box-content cf">
				<div class="shadow-box">
					<div class="message-info">
						<dl>
							<dt>From</dt>
							<dd>
								<?php if ($entry->client):?>
									<?php echo YsaHtml::link($entry->name, array('client/view/' . $entry->client->id)); ?>
								<?php else:?>
									<?php echo $entry->name?>
								<?php endif?>
							</dd>
							<dt>Email</dt>
							<dd>
								<?php echo YsaHtml::mailto($entry->email, $entry->email); ?>
							</dd>
							
							<dt>Sent Date</dt>
							<dd><?php echo Yii::app()->dateFormatter->formatDateTime($entry->created, 'medium', '') ?></dd>
						</dl>
						<div class="cf"></div>
					</div>
					<h3><?php echo $entry->subject?></h3>
					<p><?php echo $entry->message()?></p>
				</div>
			</div>
		</div>
	</section>
</div>