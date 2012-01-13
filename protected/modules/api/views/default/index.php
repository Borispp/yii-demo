<h1>Default contoller for testing</h1>
<div class="help">
	<h4>Application keys</h4>
	<table style="width:200px;">
		<tr>
			<th style="white-space: nowrap">Application ID</th>
			<th>Key</th>
		</tr>
		<?php foreach($applicationList as $obApplication):?>
		<tr>
			<td><?php echo $obApplication->id?></td>
			<td><?php echo $obApplication->appkey?></td>
		</tr>
		<?php endforeach?>
	</table>
	<?php if ($eventList):?>
	<h4>Avaluable events</h4>
	<table style="width:200px;">
		<tr>
			<th style="white-space: nowrap">Application ID</th>
			<th>id</th>
			<th>name</th>
			<th>password</th>
		</tr>
		<?php foreach($eventList as $obEvent):if (!$obEvent->user->application) continue;?>
		<tr>
			<td><?php echo $obEvent->user->application->id?></td>
			<td><?php echo $obEvent->id?></td>
			<td><?php echo $obEvent->name?></td>
			<td><?php echo $obEvent->passwd?></td>
		</tr>
		<?php endforeach?>
	</table>
	<?php endif?>
</div>
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