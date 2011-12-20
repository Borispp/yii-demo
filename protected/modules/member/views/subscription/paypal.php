<?php echo YsaHtml::pageHeaderTitle('New Subscription'); ?>
<div class="form">
	<form action="<?php echo $url?>" method="POST">
		<input type="hidden" name="cmd" value="_xclick"/>
		<input type="hidden" name="currency_code" value="<?php echo $currency?>"/>
		<input type="hidden" name="business" value="<?php echo $email?>"/>
		<input type="hidden" name="item_name" value="<?php echo $productName?>"/>
		<input type="hidden" name="amount" value="<?php echo $amount?>"/>
		<?php if ($productId):?>
			<input type="hidden" name="item_number" value="<?php echo $productId?>"/>
		<?php endif?>
		<?php if ($isTestMode):?>
			<input type="hidden" name="ipn_test" value="1"/>
		<?php endif?>
		<input type="hidden" name="notify_url" value="<?php echo $notifyUrl?>"/>
		<input type="hidden" name="return" value="<?php echo $returnUrl?>"/>
		<input type="submit" value="Pay by paypal">
	</form>
</div>