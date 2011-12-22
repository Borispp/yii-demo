<div class="g12">
	<h3>General</h3>

	<p>
		<strong>Member name</strong><br/>
		<a href="<?php echo Yii::app()->createUrl('/admin/member/view/', array(
			'id'	=> $entry->UserSubscription->Member->id
		))?>"><?php echo $entry->UserSubscription->Member->name()?></a>
	</p>
	<p>
		<strong>Membership</strong><br/>
		<a href="<?php echo Yii::app()->createUrl('/admin/membership/edit/', array(
						'id'	=> $entry->UserSubscription->Membership->id
				))?>"><?php echo $entry->UserSubscription->Membership->name?></a>
	</p>
	<p>
		<strong>Subscription</strong><br/>
		<a href="<?php echo Yii::app()->createUrl('/admin/subscription/edit/', array(
						'id'	=> $entry->UserSubscription->id
				))?>">Subscription #<?php echo $entry->UserSubscription->id?></a>
		<?php if ($entry->UserSubscription):?>
		<br/><strong>Subscription State</strong><br/>
		<?php echo $entry->UserSubscription->state()?>
		<?php endif?>
	</p>
	<p>
		<strong>Summ</strong><br/>
		<?php echo $entry->summ?> <?php echo Yii::app()->settings->get('paypal_currency')?>
		<?php if ($entry->UserSubscription->Discount):?>
		<br/><strong>Discount</strong><br/>
		<?php echo $entry->UserSubscription->Discount->summ?>%
		<?php endif?>
	</p>
	<p>
		<strong>State</strong><br/>
		<?php echo $entry->state()?>
		<?php if ($entry->state == $entry::STATE_PAID):?>
			<br/><strong>Payment Date</strong><br/>
			<?php echo date('m.d.Y', strtotime($entry->payed))?>
		<?php endif?>
	</p>
	<?php if ($entry->data):?>
	<h3>Debug Information</h3>
	<pre>
	<?php var_dump(unserialize($entry->data))?>
	</pre>
	<?php endif;?>
</div>