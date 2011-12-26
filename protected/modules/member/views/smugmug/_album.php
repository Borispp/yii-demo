<form action="" method="post">
	<h4><?php echo $album['Title']; ?> (<?php echo $images['ImageCount']?>)</h4>
	<ul>
		<?php foreach ($images['Images'] as $image) : ?>
			<li>
				<?php $data = $this->member()->smugmug()->images_getInfo('ImageID=' . $image['id'], 'ImageKey=' . $image['Key']); ?>
				<label>
					<img src="<?php echo $data['TinyURL']; ?>" alt="<?php echo $data['Caption']; ?>" />
					<input type="checkbox" name="smugmug_image[]" value="<?php echo $image['id']; ?>|<?php echo $image['Key']; ?>" />	
				</label>

			</li>
		<?php endforeach; ?>
	</ul>
	<p>
		<a href="" class="smugmug-import-selected">Import Selected</a>
		|
		<a href="" class="smugmug-check-all">Check All</a>
		|
		<a href="" class="smugmug-check-none">Check None</a>
	</p>
</form>