<div class="form pass-form">
	<form action="" method="post">
		<ul class="cf">
			<?php foreach ($images['photoSetImages'] as $image) : ?>
				<li>
					<label>
						<figure><img src="<?php echo $image['URL'] ?>" alt="<?php echo $image['FileName']; ?>" /></figure>
						<span><input type="checkbox" name="pass_image[]" value="<?php echo urlencode(serialize($image)); ?>" id="pass-image-<?php echo $image['Key']; ?>" /></span>
					</label>
				</li>
			<?php endforeach; ?>
		</ul>
		<div class="buttons">
			<?php echo YsaHtml::link('Import Selected', '#', array('class' => 'pass-import-selected btn small blue')); ?>
			<?php echo YsaHtml::link('Select All', '#', array('class' => 'pass-check-all btn small')); ?>
			<?php echo YsaHtml::link('Invert Selection', '#', array('class' => 'pass-check-invert btn small')); ?>
			<?php echo YsaHtml::link('Select None', '#', array('class' => 'pass-check-none btn small')); ?>
		</div>
		<div class="importing">
			Importing images... Please don't reload the page.
		</div>
	</form>
</div>