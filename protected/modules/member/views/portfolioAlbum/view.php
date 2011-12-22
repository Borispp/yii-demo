<section id="portfolio-album" albumid="<?php echo $entry->id; ?>">

	<?php echo YsaHtml::link('Edit Album Info', array('portfolioAlbum/edit/' . $entry->id))?>

	<?php $form = $this->beginWidget('YsaMemberForm', array(
			'id'=>'portfolio-photo-form',
			'enableAjaxValidation'=>false,
			'htmlOptions'=>array('enctype' => 'multipart/form-data'),
	)); ?>
	<?php echo $form->fileField($upload, 'photo', array()); ?>
	<?php echo $form->error($upload, 'photo'); ?>
	<?php echo YsaHtml::submitButton('Upload'); ?>

	<?php $this->endWidget();?>
	
	<div id="photo-upload-container">
		<div id="filelist" style="min-height: 100px;border: 1px solid red;">
			
		</div>
		<a href="#" id="photo-upload-browse">select files</a>
		<a href="#" id="photo-upload-submit">upload</a>
	</div>
	
	<h3>Album Photos</h3>
	<ul id="portfolio-album-photos" class="album-photos">
		<?php if (count($entry->photos())) : ?>
				<?php foreach ($entry->photos() as $photo) : ?>
				<?php echo $this->renderPartial('/portfolioPhoto/_listphoto', array(
					'entry' => $photo
				)); ?>
				<?php endforeach; ?>
		<?php endif; ?>
	</ul>
</section>