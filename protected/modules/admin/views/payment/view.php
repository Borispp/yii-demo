<div class="g12">
	<form>
		<fieldset>

			<label>General Information</label>
			<section>
				<label>Member name</label>
				<div>
					<a href="<?php echo Yii::app()->createUrl('/admin/member/view/', array(
							'id'	=> $entry->getMember()->id
						))?>"><?php echo $entry->getMember()->name()?></a>
				</div>
			</section>
			<section>
				<label>Creation Date</label>
				<div>
					<?php echo date('m.d.Y', strtotime($entry->created))?>
				</div>
			</section>
			<section>
				<label>Description</label>
				<div>
					<?php echo nl2br($entry->description)?>
				</div>
			</section>
			<section>
				<label>Notes</label>
				<div>
					<?php echo nl2br($entry->notes)?>
				</div>
			</section>
			<?php if ($entry->type == 'subscription'):?>
			<section>
				<label>Membership</label>
				<div>
					<a href="<?php echo Yii::app()->createUrl('/admin/membership/edit/', array(
							'id'	=> $entry->paymentTransactionSubscriptions[0]->subscription->Membership->id
						))?>"><?php echo $entry->paymentTransactionSubscriptions[0]->subscription->Membership->name?></a>
				</div>
			</section>
			<section>
				<label>Subscription</label>
				<div>
					<a href="<?php echo Yii::app()->createUrl('/admin/subscription/edit/', array(
							'id'	=> $entry->paymentTransactionSubscriptions[0]->subscription->id
						))?>">Subscription #<?php echo $entry->paymentTransactionSubscriptions[0]->subscription->id?></a>
					<?php if ($entry->paymentTransactionSubscriptions[0]->subscription):?>
					(<strong><?php echo $entry->paymentTransactionSubscriptions[0]->subscription->state()?></strong>)
					<?php endif?>
				</div>
			</section>
			<?php else:?>
			<section>
				<label>Application</label>
				<div>
					<a href="<?php echo Yii::app()->createUrl('/admin/application/moderate/', array(
							'id'     => $entry->paymentTransactionApplications[0]->application->id
						))?>" target="_blank">
					Application ID#<?php echo $entry->paymentTransactionApplications[0]->application->id?></a>
				</div>
			</section>
			<?php endif;?>
			<section>
				<label>Summ</label>
				<div>
					<?php echo $entry->summ?> <?php echo Yii::app()->settings->get('paypal_currency')?>
					<?/*php if ($entry->UserSubscription->Discount):?>
					(<strong>Discount <?php echo $entry->UserSubscription->Discount->summ?>%</strong>)
					<?php endif*/?>
				</div>
			</section>
			<section>
				<label>State</label>
				<div>
					<?php echo $entry->state()?>
				</div>
			</section>
			<?php if ($entry->state == PaymentTransaction::STATE_PAID && $entry->paid && $entry->paid != '0000-00-00'):?>
			<section>
				<label>Payment Date</label>
				<div>
					<?php echo date('m.d.Y', strtotime($entry->paid))?>
				</div>
			</section>
			<section>
				<label>Outer ID</label>
				<div>
					<?php echo $entry->outer_id ? $entry->outer_id : 'No outer ID'?>
				</div>
			</section>
			<?php endif?>
		</fieldset>
		<?php if ($entry->data):?>
		<fieldset>
			<label>Debug Information</label>
		<pre>
			<?php var_dump(unserialize($entry->data))?>
			</pre>
		<?php endif;?>
	</form>
</div>