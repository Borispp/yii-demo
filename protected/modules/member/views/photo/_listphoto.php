<li id="album-photo-<?php echo $entry->id?>" <?php echo $entry->isCover() ? 'class="cover"' : ''; ?>>
	<figure data-src="<?php echo $entry->url();?>">
		<?php echo $entry->preview(Yii::app()->params['member_area']['photo']['preview']['width'], Yii::app()->params['member_area']['photo']['preview']['height'], array(), isset($position)?$position:0); ?>
		<figcaption><?php echo YsaHtml::link(YsaHelpers::truncate($entry->name, 25), array('photo/view/' . $entry->id), array('title' => $entry->name)); ?></figcaption>
		<span class="menu">
			<?php echo YsaHtml::link(' ', array('photo/view/' . $entry->id), array('class' => 'view icon i_eye', 'title' => 'View Photo')); ?>
			&nbsp;|&nbsp;
			<?php echo YsaHtml::link(' ', '#', array('class' => 'setcover icon i_push_pin', 'title' => 'Make Album Cover', 'rel' => $entry->id)); ?>
			&nbsp;|&nbsp;
			<?php echo YsaHtml::link(' ', array('photo/delete/' . $entry->id), array('class' => 'del icon i_delete', 'title' => 'Delete Photo')); ?>
		</span>
		<?php echo YsaHtml::link('Sort', '#', array('class' => 'move icon i_cursor_drag_arrow')); ?>
	</figure>
</li>