<div class="w" id="portfolio">
	<p><?php echo YsaHtml::link('Add Portfolio Album', array('portfolioAlbum/create/')); ?></p>
	
	<ul id="portfolio-albums" class="albums">
		<?php foreach ($this->member()->studio->portfolio->albums as $entry) : ?>
			<li id="event-album-<?php echo $entry->id?>">
				<?php echo $entry->preview(); ?>
				<?php echo YsaHtml::link('View', array('portfolioAlbum/view/' . $entry->id), array('class' => 'view')); ?>
				<?php echo YsaHtml::link('Edit', array('portfolioAlbum/edit/' . $entry->id), array('class' => 'edit')); ?>
				<?php echo YsaHtml::link('x', array('portfolioAlbum/delete/' . $entry->id), array('class' => 'delete', 'rel' => $entry->id)); ?>
			</li>
		<?php endforeach; ?>
	</ul>
</div>