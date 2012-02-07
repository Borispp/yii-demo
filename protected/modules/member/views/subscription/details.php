<div class="w" id="client" data-clientid="<?php echo $obUserSubscription->id; ?>">
	<section class="box">
		<div class="box-title">
			<h3><?php echo $obUserSubscription->Membership->name?></h3>
			<div class="box-title-button"></div>
		</div>
		<div class="box-content cf">

			<div class="description shadow-box">
				<?php if ($obUserSubscription->Membership->description) : ?>
					<div class="title">Description</div>
					<p><?php echo $obUserSubscription->Membership->description; ?></p>
				<?php endif; ?>

				<div class="title">State</div>
				<p><?php echo $obUserSubscription->state()?></p>
				<dl>
					<dt>Start Date</dt>
					<dd><?php echo $obUserSubscription->start_date?></dd>
					<dt>Expiry Date</dt>
					<dd><?php echo $obUserSubscription->expiry_date?></dd>
				</dl>
			</div>


			<div class="main-box">
				<div class="main-box-title">
					<h3>Membership Information</h3>
					<div class="cf"></div>
				</div>
				<div class="shadow-box">
					<table class="data">
						<tr>
							<th>Name</th>
							<td><strong><?php echo $obUserSubscription->Membership->name?></strong></td>
						</tr>
						<tr>
							<th>Description</th>
							<td><?php echo $obUserSubscription->Membership->description?></td>
						</tr>
						<tr>
							<th>Duration</th>
							<td><?php echo $obUserSubscription->Membership->duration?> month<?php echo $obUserSubscription->Membership->duration > 1 ? 's' : ''?></td>
						</tr>
						<tr>
							<th>Price</th>
							<td>
								<?php if ($obUserSubscription->Discount):?>
									<s><?php echo $obUserSubscription->Membership->price()?></s>
								<?php endif;?>
								<?php echo $obUserSubscription->summ()?>
							</td>
						</tr>
					</table>
				</div>
			</div>

			<div class="main-box">
				<div class="main-box-title">
					<h3>Payment Information</h3>
					<div class="cf"></div>
				</div>
				<div class="shadow-box">
					<table class="data">
						<tr>
							<th>Opened</th>
							<td><?php echo $obUserSubscription->Transaction[0]->created?></td>
						</tr>
						<tr>
							<th>Payed</th>
							<td><?php echo $obUserSubscription->Transaction[0]->paid?></td>
						</tr>
						<tr>
							<th>Transaction System ID</th>
							<td><?php echo $obUserSubscription->Transaction[0]->outer_id?></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</section>
</div>