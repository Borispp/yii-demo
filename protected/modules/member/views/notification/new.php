<script type="text/javascript" src="<?php echo Yii::app()->baseUrl?>/resources/js/member/notification.js"></script>
<div class="w">
	<section class="box">
		<div class="box-title">
			<h3>Add Notification</h3>
		</div>
		<div class="box-content">
			<div class="shadow-box">
				<div class="form ajax-form">
					<form method="post" action="<?php echo Yii::app()->createAbsoluteUrl('/member/notification/send')?>" id="new-notification-form">
						<section class="cf">
							<label for="message">Message</label>
							<div>
								<textarea name="message" id="message" class="required" cols="40" rows="4"></textarea>
								<div id="response-message" class="errorMessage"></div>
								<input type="hidden" name="recipient" value="<?php echo $recipient?>"/>
								<input type="hidden" name="type" value="<?php echo $type?>"/>
							</div>
						</section>
						<section class="button">
							<?php echo YsaHtml::submitButton('Send', array('class' => 'blue')); ?>
						</section>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>