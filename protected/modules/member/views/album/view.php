<section id="album" albumid="<?php echo $entry->id; ?>" class="w">
	<?php echo YsaHtml::link('Edit Album Info', array('album/edit/' . $entry->id))?>

	<?php $form = $this->beginWidget('YsaMemberForm', array(
			'id'=>'album-upload-form',
			'enableAjaxValidation'=>false,
			'htmlOptions'=>array('enctype' => 'multipart/form-data'),
	)); ?>
	<?php echo $form->fileField($upload, 'photo', array()); ?>
	<?php echo $form->error($upload, 'photo'); ?>
	<?php echo YsaHtml::submitButton('Upload'); ?>

	<?php $this->endWidget();?>
	
	<div id="photo-upload-container">
		<div id="photo-filelist" style="min-height: 100px;border: 1px solid red;">
			
		</div>
		<a href="#" id="photo-upload-browse">select files</a>
		<a href="#" id="photo-upload-submit">upload</a>
	</div>
	
	<h3>Album Photos</h3>
	<ul id="album-photos" class="album-photos">
		<?php if (count($entry->photos())) : ?>
				<?php foreach ($entry->photos() as $photo) : ?>
				<?php echo $this->renderPartial('/photo/_listphoto', array(
					'entry' => $photo
				)); ?>
				<?php endforeach; ?>
		<?php endif; ?>
	</ul>
	
	<h3>Order sizes for album</h3>
	<?php $orderForm = $this->beginWidget('YsaMemberForm', array(
			'id'=>'album-size-form',
			'enableAjaxValidation'=>false,
	)); ?>
	<ul>
		<?php echo YsaHtml::checkBoxList(
			'AlbumSizes', 
			YsaHtml::listData($entry->sizes, 'id', 'id'),
			YsaHtml::listData($photoSizes, 'id', 'title'), 
			array(
				'template' => '<li>{input} {label}</li>',
				'separator' => '',
			)
		); ?>
	</ul>
	<?php echo YsaHtml::submitButton('Save'); ?>
	<?php $this->endWidget();?>
	

<?php if (!$entry->event()->isProofing()) : ?>
	<h3>Availability for sharing/order this album</h3>
	<?php $avlForm = $this->beginWidget('YsaMemberForm', array(
			'id'=>'album-availability-form',
			'enableAjaxValidation'=>false,
	)); ?>
	<p>
		<?php echo $avlForm->labelEx($availability,'can_order'); ?>
		<?php echo $avlForm->checkBox($availability, 'can_order', array('checked' => $entry->canOrder())); ?>
	</p>
	<p>
		<?php echo $avlForm->labelEx($availability,'can_share'); ?>
		<?php echo $avlForm->checkBox($availability, 'can_share', array('checked' => $entry->canOrder())); ?>
	</p>
	<p><?php echo YsaHtml::submitButton('Save'); ?></p>
	<?php $this->endWidget();?>
<?php endif;?>
</section>