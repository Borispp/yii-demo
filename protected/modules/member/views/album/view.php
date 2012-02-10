<section id="album" data-albumid="<?php echo $entry->id; ?>" class="w">

	<section class="box">
		<div class="box-title">
			<h3><?php echo $entry->name; ?></h3>
			<div class="box-title-button">
				<?php echo YsaHtml::link('<span class="icon i_pencil"></span>Edit Album Info', array('album/edit/' . $entry->id), array('class' => 'secondary iconed'))?>
			</div>
		</div>
		<div class="box-content">
			<div class="description shadow-box <?php echo strtolower($entry->state())?>">
				
				<?php if ($entry->description) : ?>
					<div class="title">Description</div>
					<p><?php echo $entry->description; ?></p>
				<?php endif; ?>
					
				<div class="title">State</div>
				<p><?php echo YsaHtml::dropDownList('state', $entry->state, $entry->getStates(), array('id' => 'description-state')); ?></p>

				<div class="title">Album Information</div>
				<dl>
					<dt>Unique ID</dt>
					<dd><?php echo $entry->id; ?></dd>
					
					<dt>Photos</dt>
					<dd><?php echo count($entry->photos); ?></dd>
					
					<dt>Created</dt>
					<dd><?php echo $entry->created('medium', null); ?></dd>
					
					<dt>Last Update</dt>
					<dd><?php echo $entry->updated('medium', null); ?></dd>
					
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
						<?php echo YsaHtml::link('Import from SmugMug', '#photo-import-smugmug-container', array('class' => 'btn fancybox fancybox.inline', 'id' => 'album-smugmug-import-button')); ?>
					<?php endif;?>
					<?php if ($this->member()->zenfolioAuthorized()) : ?>
						<?php echo YsaHtml::link('Import from ZenFolio', '#photo-import-zenfolio-container', array('class' => 'btn fancybox fancybox.inline', 'id' => 'album-zenfolio-import-button')); ?>
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
					<?php echo $avlForm->labelEx($availability,'can_share'); ?>
					<div><?php echo $avlForm->checkBox($availability, 'can_share', array('checked' => $availability->can_share)); ?></div>
				</section>
				<section class="cf">
					<?php echo $avlForm->labelEx($availability,'can_save'); ?>
					<div><?php echo $avlForm->checkBox($availability, 'can_save', array('checked' => $availability->can_save)); ?></div>
				</section>
				<div class="button">
					<?php echo YsaHtml::submitLoadingButton('Save', array('class' => 'blue')); ?>
				</div>
				<?php $this->endWidget();?>
			</div>
		</div>
	</section>

	<?php endif;?>
	
    <div id="photo-upload-container" class="multi-uploader">
		<p>You browser doesn't have HTML5 support.</p>
	</div>
	<?php if ($this->member()->smugmugAuthorized()) : ?>
		<section id="photo-import-smugmug-container" class="smugmug-import box">
			<div class="box-title">
				<h3>Import from SmugMug Album</h3>
			</div>
			<div class="box-content">
				<div class="data">
					<p>Please note that large amount of photos takes more time.</p>
					<select name="album">
						<option value="">&ndash;&ndash;&ndash;</option>
						<?php foreach ($this->member()->smugmug()->albums_get('Extras=ImageCount') as $album) : ?>
							<option value="<?php echo $album['id']; ?>|<?php echo $album['Key']; ?>"><?php echo $album['Title']; ?> (<?php echo $album['ImageCount']?>)</option>
						<?php endforeach; ?>
					</select>
					<input type="button" value="Show Photos" />
				</div>
				<div class="import cf"></div>
				<div class="loading">Loading album images. Please be patient &mdash; it takes a while...</div>
			</div>
		</section>
	<?php endif; ?>
	<?php if ($this->member()->zenfolioAuthorized()) : ?>
		<section id="photo-import-zenfolio-container" class="zenfolio-import box">
			<div class="box-title">
				<h3>Import from ZenFolio Album</h3>
			</div>
			<div class="box-content">
				<div class="data">
					<p>Please note that large amount of photos takes more time.</p>
					<p>Sometimes SmugMug doesn't allow to show protected galleries images on the web. But they can be imported even if you don't see them. </p>
					<select name="album">
						<option value="">&ndash;&ndash;&ndash;</option>
						<?php foreach ($zenfolioHierarchy['Elements'] as $element) : ?>
							<option value="<?php echo $element['Id']; ?>"><?php echo $element['Title']; ?> (<?php echo $element['PhotoCount']?>)</option>
						<?php endforeach; ?>
					</select>
					<input type="button" value="Show Photos" />
				</div>
				<div class="import cf"></div>
				<div class="loading">Loading album images. Please be patient &mdash; it takes a while...</div>
			</div>
		</section>
	<?php endif; ?>
</section>
