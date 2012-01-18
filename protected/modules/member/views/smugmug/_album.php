<div class="form smugmug-form">
	<h3><?php echo $album['Title']; ?> (<?php echo $images['ImageCount']?>)</h3>
	<form action="" method="post">
		<ul class="cf">
			<?php foreach ($images['Images'] as $image) : ?>
				<li>
					<?php $data = $this->member()->smugmug()->images_getInfo('ImageID=' . $image['id'], 'ImageKey=' . $image['Key']); ?>
					<label>
						<figure><img src="<?php echo $data['TinyURL']; ?>" alt="<?php echo $data['Caption']; ?>" /></figure>
						<span><input type="checkbox" name="smugmug_image[]" value="<?php echo $image['id']; ?>|<?php echo $image['Key']; ?>" id="smugmug-image-<?php echo $image['Key']?>" />	</span>
					</label>

				</li>
			<?php endforeach; ?>
		</ul>
		<div class="buttons">
			<?php echo YsaHtml::link('Import Selected', '#', array('class' => 'smugmug-import-selected btn small blue')); ?>
			<?php echo YsaHtml::link('Select All', '#', array('class' => 'smugmug-check-all btn small')); ?>
			<?php echo YsaHtml::link('Invert Selection', '#', array('class' => 'smugmug-check-invert btn small')); ?>
			
			<?php echo YsaHtml::link('Select None', '#', array('class' => 'smugmug-check-none btn small')); ?>
		</div>
		
		<div class="importing">
			Importing images... Please don't reload the page.
		</div>
		
	</form>
</div>