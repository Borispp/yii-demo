<section class="body w">
	<h3>Going to paysystem...</h3>
	<div class="form">
		<form action="<?php echo $formAction?>" method="POST" id="paypal_form">
			<?php foreach($formFields as $field => $value):?>
				<input type="hidden" name="<?php echo $field?>" value="<?php echo $value?>"/>
			<?php endforeach?>
			<input type="submit" value="Proceed to Payment"/>
		</form>
	</div>
</section>
<script type="text/javascript">
	setTimeout(function(){
		$('#paypal_form').submit();
	}, 1000);
</script>