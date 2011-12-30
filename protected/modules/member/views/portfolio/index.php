<div class="w" id="portfolio">
	<p class="r"><?php echo YsaHtml::link('Add Portfolio Album', array('portfolioAlbum/create/'), array('class' => 'btn')); ?></p>
	<ul id="portfolio-albums" class="albums cf">
		<?php foreach ($this->member()->studio->portfolio->albums as $entry) : ?>
			<li id="event-album-<?php echo $entry->id?>">
				<figure><?php echo $entry->preview(); ?></figure>
				<figcaption><?php echo $entry->name; ?></figcaption>
				<div class="menu">
					<?php echo YsaHtml::link('View', array('portfolioAlbum/view/' . $entry->id), array('class' => 'view')); ?>
					<span>|</span>
					<?php echo YsaHtml::link('Edit', array('portfolioAlbum/edit/' . $entry->id), array('class' => 'edit')); ?>
					<span>|</span>
					<?php echo YsaHtml::link('Delete', array('portfolioAlbum/delete/' . $entry->id), array('class' => 'delete', 'rel' => $entry->id)); ?>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
</div>