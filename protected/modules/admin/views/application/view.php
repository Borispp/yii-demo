<div class="g12">
	<?php foreach($options as $section => $properties):?>
	<h3><?php echo $section?></h3>
		<?php foreach($properties as $label => $value):?>
			<p><strong><?php echo $label?></strong><br/><?php echo $value?></p>
		<?endforeach?>
	<?endforeach?>
</div>