<div class="w" id="portfolio">
	<h2>Albums</h2>
	
	
	<p><?php echo CHtml::link('Add Portfolio Album', array('portfolioAlbum/create/')); ?></p>
	
	<ul>
		<?php foreach ($this->member()->studio()->portfolio()->albums() as $entry) : ?>
			
		<?php endforeach; ?>
	</ul>
	
	<ul id="portfolio-albums" class="albums">
		<?php foreach ($this->member()->studio()->portfolio()->albums() as $album) : ?>
			<li id="event-album-<?php echo $album->id?>">
				<?php echo $album->preview(); ?>
				<?php echo YsaHtml::link('View', array('portfolioAlbum/view/' . $album->id), array('class' => 'view')); ?>
				<?php echo YsaHtml::link('Edit', array('portfolioAlbum/edit/' . $album->id), array('class' => 'edit')); ?>
				<?php echo YsaHtml::link('x', array('portfolioAlbum/delete/' . $album->id), array('class' => 'delete', 'rel' => $album->id)); ?>
			</li>
		<?php endforeach; ?>
	</ul>
</div>