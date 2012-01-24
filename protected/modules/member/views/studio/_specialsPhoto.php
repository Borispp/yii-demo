<?php if ('pdf' == $entry->specialsExtention()) : ?>
	<div class="pdf-wrapper cf">		
		<?php echo YsaHtml::link($entry->specials(), $entry->specialsUrl(), array('class' => 'pdf')); ?>
		<?php echo YsaHtml::link('x', array('deleteSpecials'), array('class' => 'btn delete red small', 'title' => 'Remove PDF')); ?>
	</div>
<?php else:?>
	<div class="image-wrapper cf">
		<?php echo YsaHtml::link($entry->specials(), $entry->specialsUrl(), array('class' => 'image')); ?>
		<?php echo YsaHtml::link('x', array('deleteSpecials'), array('class' => 'btn delete red small', 'title' => 'Remove Image')); ?>
	</div>
<?php endif; ?>


