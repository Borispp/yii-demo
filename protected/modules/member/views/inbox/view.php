<div class="w" id="client" data-inboxid="<?php echo $entry->id; ?>">
	<section class="box">
		<div class="box-title">
			<h3><?php echo $entry->subject; ?></h3>
			<div class="box-title-button">
				<?php echo YsaHtml::link('<span class="icon i_spechbubble_sq_line"></span>Respond with Push Notification ', '/member/notification/new/recipient/'.$entry->client->id.'/type/client', array('class' => 'secondary iconed', 'id' => 'send-push-link')); ?>
			</div>
		</div>
		<div class="box-content cf">
			<div class="description shadow-box">
				<div class="title">From</div>
				<p><td><?php echo YsaHtml::link($entry->client->name, array('client/view/' . $entry->client->id)); ?></td></p>
				<div class="title">Sent Date</div>
				<p><?php echo Yii::app()->dateFormatter->formatDateTime($entry->created, 'medium', '') ?></p>
			</div>
			<div class="main-box">
				<div class="main-box-title">
					<h3><?php echo $entry->subject?></h3>
					<div class="cf"></div>
				</div>
				<div class="shadow-box">
					<p><?php echo $entry->message()?></p>
				</div>
			</div>
		</div>
	</section>
</div>