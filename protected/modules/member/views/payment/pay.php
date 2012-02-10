<div class="w" id="subscription-add">
	<section class="box">
		<div class="box-title"><h3>Going to paysystem...</h3></div>
		<div class="box-content">
			<form action="<?php echo $formAction?>" method="POST" id="paypal_form">
				<?php foreach($formFields as $field => $value):?>
				<input type="hidden" name="<?php echo $field?>" value="<?php echo $value?>"/>
				<?php endforeach?>
				<input type="submit" value="Proceed to Payment"/>
			</form>
		</div>
	</section>
	<?php if ($enableRedirect):?>
	<script type="text/javascript">
		setTimeout(function(){
			$('#paypal_form').submit();
		}, 1000);
	</script>
	<?php endif;?>
</div>