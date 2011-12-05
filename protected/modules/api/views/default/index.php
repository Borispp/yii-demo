<?php
$this->breadcrumbs=array(
	$this->module->id,
);
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<h1>Default contoller for testing</h1>
<input type="text" name="url" id="url" style="width: 500px;" value="http://<?php echo $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]?>" /><br/>
<textarea name="request" id="request" cols="30" rows="10"></textarea>
<input type="button" id="send" value="Send"/>
<div class="response">

</div>
<script type="text/javascript">
	$(window).load(function(){
		$('#send').click(function(){
			$.ajax({
				type: 'POST',
				url: $('#url').val(),
				data: $('#request').val(),
				success: function(data){
					$('.response').html(data);
				},
				dataType:'html'
			});
		});
	});
</script>