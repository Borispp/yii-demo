<?php echo YsaHtml::pageHeaderTitle('Subscription paid'); ?>
<section class="body w">
	<h3>Congratulations!</h3>
	<p class="desc">
		Now you're subscribed to YSA.
		Your <a href="<?php echo Yii::app()->createUrl('/member/subscription/list/')?>">subscription</a> will be enabled from <?php echo $obUserSubscription->start_date?> to <?php echo $obUserSubscription->expiry_date?>
	</p>
</section>