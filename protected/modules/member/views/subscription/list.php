<?php echo YsaHtml::pageHeaderTitle('My subscriptions'); ?>
<section class="body w">
	<?php if (count($subscriptions) < 1):?>
	<h3>You have no subscriptions.</h3>
	<?php else:?>
	<table>
		<tr>
			<th>Subscription</th>
			<th>Start date</th>
			<th>End date</th>
			<th>State</th>
			<th></th>
		</tr>
		<?php foreach($subscriptions as $obUserSubscription):?>
		<tr>
			<td><?php echo $obUserSubscription->Membership->name?></td>
			<th><?php echo $obUserSubscription->start_date?></th>
			<th><?php echo $obUserSubscription->expiry_date?></th>
			<th><?php echo $obUserSubscription->labelState()?></th>
			<th><?php if ($obUserSubscription->state == $obUserSubscription::STATE_INACTIVE):?>
				<?php if ($obUserSubscription->Transaction):?>
				<a href="<?php echo Yii::app()->createUrl('/member/subscription/paypal/', array(
					'id'	=> $obUserSubscription->Transaction->id
				))?>">Pay</a>
				<?php endif?>
				<?php endif?>
			</th>
		</tr>
		<?php endforeach?>
	</table>
	<?php endif?>
	<a href="<?php echo Yii::app()->createUrl('/member/subscription/new/')?>">Add new subscription</a>
</section>