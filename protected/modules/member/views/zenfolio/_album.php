<div class="form zenfolio-form">
	<form action="" method="post">
		<ul class="cf">
			<?php foreach ($images as $image) : ?>
				<li>
					<label>
						<figure><img src="<?php echo phpZenfolio::imageUrl($image, 0) ?>" alt="<?php echo $image['Title']; ?>" /></figure>
						<span><input type="checkbox" name="zenfolio_image[]" value="<?php echo $image['Id']; ?>" id="zenfolio-image-<?php echo $image['Id']; ?>" />	</span>
					</label>
				</li>
			<?php endforeach; ?>
		</ul>
		<div class="buttons">
			<?php echo YsaHtml::link('Import Selected', '#', array('class' => 'zenfolio-import-selected btn small blue')); ?>
			<?php echo YsaHtml::link('Select All', '#', array('class' => 'zenfolio-check-all btn small')); ?>
			<?php echo YsaHtml::link('Invert Selection', '#', array('class' => 'zenfolio-check-invert btn small')); ?>
			<?php echo YsaHtml::link('Select None', '#', array('class' => 'zenfolio-check-none btn small')); ?>
		</div>
		<div class="importing">
			Importing images... Please don't reload the page.
		</div>
	</form>
</div>