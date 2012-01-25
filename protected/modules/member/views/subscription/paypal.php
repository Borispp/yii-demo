<section class="body w">
	<h3>Going to paypal...</h3>
	<div class="form">
		<form action="<?php echo $url?>" method="POST" id="paypal_form">
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
</section>
<script type="text/javascript">
	setTimeout(function(){
		$('#paypal_form').submit();
	}, 1000);
</script>