<div class="w" id="subscription-list">
	<section class="box">
		<div class="box-title">
			<h3>Your Subscriptions</h3>
			<div class="box-title-button">
				<?php echo YsaHtml::link('Add New Subscription', array('new'), array('class' => 'secondary')); ?>
			</div>
		</div>
		<div class="box-content">
			<div class="shadow-box">
				<table class="data">
					<thead>
						<tr>
							<th>Subscription</th>
							<th class="w_10">Start date</th>
							<th class="w_10">End date</th>
							<th class="w_10">State</th>
							<th class="w_15">Actions</th>
						</tr>
					</thead>
					<?php if (count($subscriptions) < 1): ?>
						<h3>You have no subscriptions.</h3>
					<?php else: ?>
						<tbody>
							<?php foreach($subscriptions as $obUserSubscription):?>
							<tr>
								<td>
									<span class="title">
										<strong><?php echo $obUserSubscription->Membership->name?></strong>
									</span>
								</td>
								<td>
									<?php echo Yii::app()->dateFormatter->formatDateTime($obUserSubscription->start_date, 'medium', null); ?>
								</td>
								<td>
									<?php echo Yii::app()->dateFormatter->formatDateTime($obUserSubscription->expiry_date, 'medium', null); ?>
								</td>
								<td><span class="<?php echo strtolower($obUserSubscription->labelState()); ?>"><?php echo $obUserSubscription->labelState()?></span></td>
								<td class="actions">
									<?php echo YsaHtml::link('Details', array('subscription/details/id/' . $obUserSubscription->id . '/'), array('class' => 'btn small')); ?>
								</td>
							</tr>
							<?php endforeach;?>
						</tbody>
					<?php endif?>
				</table>
			</div>
		</div>
	</section>
</div> 
