<li id="album-photo-<?php echo $entry->id?>" albumphotoid="<?php echo $entry->id?>">
	<figure>
		<?php echo YsaHtml::link(
			YsaHtml::image(
				$entry->previewUrl(
					Yii::app()->params['member_area']['photo']['preview']['width'], 
					Yii::app()->params['member_area']['photo']['preview']['height']
				),
				$entry->alt
			),
			array('photo/view/' . $entry->id)
		); ?>
		<?php echo YsaHtml::link('x', array('photo/delete/' . $entry->id), array('class' => 'delete', 'rel' => $entry->id)); ?>
	</figure>
</li>