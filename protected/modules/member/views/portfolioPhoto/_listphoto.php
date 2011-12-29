<li id="album-photo-<?php echo $entry->id?>" albumphotoid="<?php echo $entry->id?>" <?php echo $entry->isCover() ? 'class="cover"' : ''; ?>>
	<figure><?php echo $entry->preview(Yii::app()->params['member_area']['photo']['preview']['width'], Yii::app()->params['member_area']['photo']['preview']['height']); ?></figure>
	<div class="menu">
		<?php echo YsaHtml::link('View', array('portfolioPhoto/view/' . $entry->id), array('class' => 'view')); ?>
		<?php echo YsaHtml::link('Set Cover', array('portfolioAlbum/setCover/' . $entry->id), array('class' => 'setcover', 'rel' => $entry->id)); ?>
		<?php echo YsaHtml::link('Delete', array('portfolioPhoto/delete/' . $entry->id), array('class' => 'delete', 'rel' => $entry->id)); ?>
	</div>
</li>