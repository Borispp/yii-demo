<li id="event-album-<?php echo $album->id?>" class="<?php echo strtolower($album->state()); ?>">
	<figure>
		<?php echo $album->preview(); ?>
		<figcaption><?php echo YsaHtml::link(YsaHelpers::truncate($album->name, 25), array('album/view/' . $album->id), array('title' => $album->name)); ?></figcaption>
		<span class="menu">
			<?php echo YsaHtml::link('View', array('album/view/' . $album->id), array('class' => 'view icon i_eye', 'title' => 'View Album')); ?>
			&nbsp;|&nbsp;
			<?php echo YsaHtml::link('Edit', array('album/edit/' . $album->id), array('class' => 'edit icon i_pencil', 'title' => 'Edit Album Details')); ?>
			<?php if (!$event->isProofing()) : ?>
				&nbsp;|&nbsp;
				<?php echo YsaHtml::link('Delete', array('album/delete/' . $album->id), array('class' => 'del icon i_delete', 'rel' => $album->id, 'title' => 'Delete Album')); ?>
			<?php endif; ?>
		</span>
		<?php echo YsaHtml::link('Sort', '#', array('class' => 'move icon i_cursor_drag_arrow')); ?>
	</figure>
</li>