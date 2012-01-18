<section id="album" data-albumid="<?php echo $entry->id; ?>" class="w">

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
	
	<section class="box">
		<div class="box-title">
			<h3><?php echo $entry->name; ?></h3>
			<div class="box-title-button">
				<?php echo YsaHtml::link('Edit Album Info', array('album/edit/' . $entry->id), array('class' => 'btn blue'))?>
			</div>
		</div>
		<div class="box-content">
			<div class="description shadow-box">
				
				<div class="title">Album Information</div>
				
				<?php if ($entry->description) : ?>
					<p><?php echo $entry->description; ?></p>
				<?php endif; ?>
				
				<dl>
					<dt>Unique ID</dt>
					<dd><?php echo $entry->id; ?></dd>

					<dt>State</dt>
					<dd><?php echo $entry->state(); ?></dd>
					
					<dt>Photos</dt>
					<dd><?php echo count($entry->photos); ?></dd>
					
					<dt>Created</dt>
					<dd><?php echo Yii::app()->dateFormatter->format('MM/dd/yy', $entry->created); ?></dd>
					
					<dt>Last Update</dt>
					<dd><?php echo Yii::app()->dateFormatter->format('MM/dd/yy', $entry->updated); ?></dd>
					
					<?php if ($entry->place) : ?>
						<dt>Place</dt>
						<dd><?php echo $entry->place; ?></dd>
					<?php endif; ?>
				</dl>
			</div>
			<div class="main-box">
				<div class="main-box-title">
					<h3>
						Album Photos
						<?php echo YsaHtml::link('Slideshow', '#', array('class' => 'btn small', 'id' => 'album-slideshow-button'))?>
					</h3>
					<?php echo YsaHtml::link('Upload Photos', '#photo-upload-container', array('class' => 'btn blue fancybox fancybox.inline', 'id' => 'album-upload-photos-button')); ?>
					<?php if ($this->member()->smugmugAuthorized()) : ?>
					
						<?php echo YsaHtml::link('Import from SmugMug', '#', array('class' => 'btn blue', 'id' => 'album-smugmug-import-button')); ?>
					<?php endif;?>
					
					
					<div class="cf"></div>
				</div>
				<ul id="album-photos" class="album-photos cf">
					<?php if (count($entry->photos)) : ?>
							<?php foreach ($entry->photos as $photo) : ?>
							<?php echo $this->renderPartial('/photo/_listphoto', array(
								'entry' => $photo
							)); ?>
							<?php endforeach; ?>
					<?php endif; ?>
				</ul>			
			</div>
			<div class="cf"></div>
		</div>
	</section>
	
	
	

	
	<?/*
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
	*/?>
	<?php if (!$entry->event->isProofing()) : ?>
	
	
	<section class="box">
		<div class="box-title">
			<h3>Availability for sharing/order this album</h3>
		</div>
		<div class="box-content">
			<div id="album-order-availability" class="form standart-form">

				<?php $avlForm = $this->beginWidget('YsaMemberForm', array(
						'id'=>'album-availability-form',
						'action' => array('album/saveAvailability/' . $entry->id)
				)); ?>
				<section class="cf">
					<?php echo $avlForm->labelEx($availability,'order_link'); ?>
					<div><?php echo $avlForm->textField($availability, 'order_link'); ?></div>
				</section>

				<section class="cf">
					<?php echo $avlForm->labelEx($availability,'can_order'); ?>
					<div><?php echo $avlForm->checkBox($availability, 'can_order', array('checked' => $entry->canOrder())); ?></div>
				</section>
				<section class="cf">
					<?php echo $avlForm->labelEx($availability,'can_share'); ?>
					<div><?php echo $avlForm->checkBox($availability, 'can_share', array('checked' => $entry->canShare())); ?></div>
				</section>
				<div class="button">
					<?php echo YsaHtml::submitButton('Save', array('class' => 'blue')); ?>
				</div>
				<?php $this->endWidget();?>
			</div>
		</div>
	</section>
	
    <div id="photo-upload-container" class="multi-uploader">
		<p>You browser doesn't have HTML5 support.</p>
	</div>	

	<?php endif;?>
</section>
