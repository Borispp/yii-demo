<div class="g12">
	<form>
		<?php foreach($options as $section => $properties):?>
		<fieldset>
			<label><?php echo $section?></label>
			<?php foreach($properties as $label => $value):?>
			<section>
				<label><?php echo $label?></label>
				<div><?php echo $value?></div>
			</section>
			<?endforeach?>
		</fieldset>
		<?endforeach?>
		<fieldset>
			<label>Moderator Toolbar</label>
			<section>
				<label>State</label>
				<div><?php echo $entry->state()?></div>
			</section>
		</fieldset>
	</form>
</div>