<div class="form">
	<script type="text/javascript" src="<?php echo Yii::app()->baseUrl?>/resources/js/member/notification.js"></script>
	<form method="post" action="<?php echo Yii::app()->createAbsoluteUrl('/member/notification/send')?>" id="new-notification-form">
		<input type="hidden" name="type" value="<?php echo $type?>"/>
		<input type="hidden" name="recipient" value="<?php echo $recipient?>"/>
		<section>
			<label for="message"></label>
			<div>
				<textarea name="message" id="message" class="required" cols="40" rows="4"></textarea>
			</div>
		</section>
		<section class="button">
			<input type="submit" value="Send"/>
		</section>
	</form>
	<section id="response-message">

		</section>
</div>