<section id="album" albumid="<?php echo $entry->id; ?>">
	<?php echo YsaHtml::pageHeaderTitle($entry->name); ?>

	<?php echo YsaHtml::link('Edit Album Info', array('album/edit/' . $entry->id))?>


	<?php $form = $this->beginWidget('YsaMemberForm', array(
			'id'=>'copyrights-step-form',
			'enableAjaxValidation'=>false,
			'htmlOptions'=>array('enctype' => 'multipart/form-data'),
	)); ?>
	<?php echo $form->fileField($upload, 'photo', array()); ?>
	<?php echo $form->error($upload, 'photo'); ?>
	<?php echo YsaHtml::submitButton('Upload'); ?>

	<?php $this->endWidget();?>

	<h3>Album Photos</h3>
	<ul id="album-photos">
		<?php if (count($entry->photos())) : ?>
				<?php foreach ($entry->photos() as $photo) : ?>
				<?php echo $this->renderPartial('/photo/_listphoto', array(
					'entry' => $photo
				)); ?>
				<?php endforeach; ?>
		<?php endif; ?>
	</ul>
</section>