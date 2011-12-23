<div class="g12">
	<form>
		<fieldset>

			<label>General Information</label>
			<section>
				<label>Member name</label>
				<div>
					<a href="<?php echo Yii::app()->createUrl('/admin/member/view/', array(
							'id'	=> $entry->UserSubscription->Member->id
						))?>"><?php echo $entry->UserSubscription->Member->name()?></a>
				</div>
			</section>
			<section>
				<label>Membership</label>
				<div>
					<a href="<?php echo Yii::app()->createUrl('/admin/membership/edit/', array(
							'id'	=> $entry->UserSubscription->Membership->id
						))?>"><?php echo $entry->UserSubscription->Membership->name?></a>
				</div>
			</section>
			<section>
				<label>Subscription</label>
				<div>
					<a href="<?php echo Yii::app()->createUrl('/admin/subscription/edit/', array(
							'id'	=> $entry->UserSubscription->id
						))?>">Subscription #<?php echo $entry->UserSubscription->id?></a>
					<?php if ($entry->UserSubscription):?>
					(<strong><?php echo $entry->UserSubscription->state()?></strong>) 
					<?php endif?>
				</div>
			</section>
			<section>
				<label>Summ</label>
				<div>
					<?php echo $entry->summ?> <?php echo Yii::app()->settings->get('paypal_currency')?>
					<?php if ($entry->UserSubscription->Discount):?>
					(<strong>Discount <?php echo $entry->UserSubscription->Discount->summ?>%</strong>)
					<?php endif?>
				</div>
			</section>
			<section>
				<label>State</label>
				<div>
					<?php echo $entry->state()?>
				</div>
			</section>
			<?php if ($entry->state == $entry::STATE_PAID && $entry->payed && $entry->payed != '0000-00-00'):?>
			<section>
				<label>Payment Date</label>
				<div>
					<?php echo date('m.d.Y', strtotime($entry->payed))?>
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