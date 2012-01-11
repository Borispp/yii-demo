<section id="album" albumid="<?php echo $entry->id; ?>" class="w">
	<?php echo YsaHtml::link('Edit Album Info', array('album/edit/' . $entry->id))?>
	
	<div id="photo-upload-container">
		<div id="photo-filelist" style="min-height: 100px;border: 1px solid red;"></div>
		<a href="#" id="photo-upload-browse">select files</a>
		<a href="#" id="photo-upload-submit">upload</a>
                
                <noscript>Please, turn on JavaScript in your browser to enable file uploading</noscript>
	</div>
	
	<?php if ($this->member()->smugmugAuthorized()) : ?>
		<div id="photo-import-smugmug-container" class="smugmug-import">
			<div class="data">
				Import from SmugMug Album
				<select name="album">
					<option value="">&ndash;&ndash;&ndash;</option>
					<?php foreach ($this->member()->smugmug()->albums_get() as $album) : ?>
						<option value="<?php echo $album['id']; ?>|<?php echo $album['Key']; ?>"><?php echo $album['Title']; ?></option>
					<?php endforeach; ?>
				</select>
				<input type="button" value="Show Photos" />
			</div>
			<div class="import"></div>
			<div class="loading">Loading album images. Please be patient &mdash; it takes a while...</div>
		</div>
	<?php endif; ?>
	
	<h3>Album Photos</h3>
	<ul id="album-photos" class="album-photos cf">
		<?php if (count($entry->photos)) : ?>
				<?php foreach ($entry->photos as $photo) : ?>
				<?php echo $this->renderPartial('/photo/_listphoto', array(
					'entry' => $photo
				)); ?>
				<?php endforeach; ?>
		<?php endif; ?>
	</ul>
	
	
	
	<div id="album-order-sizes">
		<h3>Order sizes for album</h3>
		<?php $this->beginWidget('YsaMemberForm', array(
				'id'=>'album-size-form',
				'action' => array('album/saveSizes/' . $entry->id)
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
	</div>
	
	<?php if (!$entry->event->isProofing()) : ?>
		<div id="album-order-availability">
			<h3>Availability for sharing/order this album</h3>
			<?php $avlForm = $this->beginWidget('YsaMemberForm', array(
					'id'=>'album-availability-form',
					'action' => array('album/saveAvailability/' . $entry->id)
			)); ?>
			<p>
				<?php echo $avlForm->labelEx($availability,'can_order'); ?>
				<?php echo $avlForm->checkBox($availability, 'can_order', array('checked' => $entry->canOrder())); ?>
			</p>
			<p>
				<?php echo $avlForm->labelEx($availability,'can_share'); ?>
				<?php echo $avlForm->checkBox($availability, 'can_share', array('checked' => $entry->canShare())); ?>
			</p>
			<p><?php echo YsaHtml::submitButton('Save'); ?></p>
			<?php $this->endWidget();?>
		</div>
	<?php endif;?>
</section>
