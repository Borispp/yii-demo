<div class="w">
	<section class="box">
		<div class="box-title">
			<h3>Edit Event</h3>
			<div class="box-title-button">
				<?php echo YsaHtml::link('<span class="icon i_bell"></span>Send Push Notification', '/member/notification/new?type=event&recipient='.$entry->id, array('class' => 'secondary iconed', 'id' => 'send-push-link')); ?>
			</div>
		</div>
		<div class="box-content">
			<?php $this->renderPartial('_form', array(
				'entry' => $entry,
			)); ?>
		</div>
	</section>
</div>