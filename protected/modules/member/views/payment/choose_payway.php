<div class="w" id="subscription-add">
	<section class="box">
		<div class="box-title">
			<h3>Select Pay System</h3>
		</div>
		<?php //@todo make new class specially for payment?>
		<div class="box-content subscription-list">
			<section class="part shadow-box">
				<?php echo YsaHtml::form(array('payment/pay/payway/paypal/'))?>
				<?php echo YsaHtml::hiddenField('type', $type)?>
				<?php echo YsaHtml::hiddenField('summ', $summ)?>
				<?php echo YsaHtml::hiddenField('item_id', $item_id)?>
				<h3>Paypal</h3>
				<p>PayPal is an American-based global e-commerce business allowing payments and
				   money transfers to be made through the Internet.</p>
				<div class="button">
					<?php echo YsaHtml::submitButton('Use'); ?>
				</div>
				<?php echo YsaHtml::endForm()?>
			</section>
			<section class="part shadow-box">
				<?php echo YsaHtml::form(array('payment/pay/payway/authorizenet/'))?>
				<?php echo YsaHtml::hiddenField('type', $type)?>
				<?php echo YsaHtml::hiddenField('summ', $summ)?>
				<?php echo YsaHtml::hiddenField('item_id', $item_id)?>
				<h3>Authorize.net</h3>
				<p>Since 1996, Authorize.Net has been a leading provider of payment gateway services, managing the
				   submission of billions of transactions.</p>
				<div class="button">
					<?php echo YsaHtml::submitButton('Use'); ?>
				</div>
				<?php echo YsaHtml::endForm()?>
			</section>
			<div class="clear"></div>
		</div>
	</section>
</div>

