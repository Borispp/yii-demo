<div class="w" id="photo" data-id="<?php echo $entry->id; ?>">
	
	<section class="box">
		<div class="box-title">
			<h3><?php echo $entry->title(); ?></h3>
			<?php if (!$entry->album->event->isPublic()) : ?>
				<div class="box-title-button">
					<?php echo $entry->shareLink('Share URL', array('class' => 'secondary', 'rel' => 'external')); ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="box-content">
			<div class="shadow-box cf">
				<figure>
					<?php echo YsaHtml::link($entry->full(), $entry->fullUrl(), array('class' => 'fancybox')); ?>
				</figure>
				<div class="description">
					
					
					<div class="container container-state">
						<div class="title">State</div>
						<div class="content">
							<?php echo YsaHtml::dropDownList('state', $entry->state, $entry->getStates(), array('id' => 'description-state')); ?>
						</div>
					</div>
					
					<div class="container">
						<div class="title">Information</div>
						<div class="content cf">
							<dl>
								<dt>Name</dt>
								<dd title="<?php echo $entry->name; ?>"><?php echo YsaHelpers::truncate($entry->name, 25); ?></dd>
								
								<dt>Mime Type</dt>
								<dd><?php echo $entry->meta_type; ?></dd>
								
								<dt>Size</dt>
								<dd><?php echo $entry->size(); ?></dd>
								
								<dt>Original Size</dt>
								<dd><?php echo $entry->originalSize(); ?></dd>
								
								<dt>Created</dt>
								<dd><?php echo $entry->created('medium', null); ?></dd>
							</dl>
						</div>
					</div>
					
					<div class="container">
						<div class="title">Meta Data</div>
						<div class="content cf">
							<?php if ($entry->exif()) : ?>
							<dl>
								<dt>ISO</dt>
								<dd><?php echo $entry->exif('iso'); ?></dd>
								
								<dt>Aperture</dt>
								<dd><?php echo $entry->exif('aperture'); ?></dd>
								
								<dt>Shutter Speed</dt>
								<dd><?php echo $entry->exif('exposure_time'); ?></dd>
								
								<dt>White Balance</dt>
								<dd><?php echo $entry->exif('white_balance'); ?></dd>
								
								<dt>Focal Length</dt>
								<dd><?php echo $entry->exif('focal_length'); ?></dd>
							</dl>
							<?php endif; ?>
						</div>
					</div>
					
					<div class="container">
						<div class="title">Edit</div>
						<div class="content cf">
							<ul class="edit">
								<li><?php echo YsaHtml::link('<span></span>Rotate Left', array('photo/redact/photoId/' . $entry->id . '/act/rotate/p/left/')); ?></li>
								<li><?php echo YsaHtml::link('<span></span>Rotate Right', array('photo/redact/photoId/' . $entry->id . '/act/rotate/p/left/')); ?></li>
								<li><?php echo YsaHtml::link('<span></span>Flip Vertically', array('photo/redact/photoId/' . $entry->id . '/act/flip/p/vert/')); ?></li>
								<li><?php echo YsaHtml::link('<span></span>Flip Horizontally', array('photo/redact/photoId/' . $entry->id . '/act/flip/p/horiz/')); ?></li>
								<li><?php echo YsaHtml::link('<span></span>Restore from Original', array('photo/restore/photoId/' . $entry->id . '/')); ?></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	
	
	
	<?/*
	<section class="box">
		<div class="box-title">
			<h3>Availability for sharing/order this photo</h3>
		</div>
		<div class="box-content">
			<div class="shadow-box">
				<?php if (!$entry->album->event->isProofing()) : ?>
					<div class="form standart-form">
						<?php $avlForm = $this->beginWidget('YsaMemberForm', array(
								'id'=>'photo-availability-form',
								'action' => array('photo/saveAvailability/' . $entry->id),
						)); ?>
						
						<section class="cf">
							<?php echo $avlForm->labelEx($availability,'can_order'); ?>
							<div>
								<?php echo $avlForm->checkBox($availability, 'can_order', array('checked' => $entry->canOrder(), 'disabled' => !$entry->album->canOrder())); ?>
							</div>
						</section>
						<section class="cf">
							<?php echo $avlForm->labelEx($availability,'can_share'); ?>
							<div>
								<?php echo $avlForm->checkBox($availability, 'can_share', array('checked' => $entry->canShare(), 'disabled' => !$entry->album->canShare())); ?>
							</div>
						</section>

						<div class="button">
							<?php echo YsaHtml::submitButton('Save'); ?>
						</div>

						<?php $this->endWidget();?>
					</div>
				<?php endif;?>
			</div>
		</div>
	</section>
	*/?>
	
	<?php if ($entry->canBeCommented()) : ?>
		<section class="box">
			<div class="box-title">
				<h3>Photo Comments</h3>
			</div>
			<div class="box-content">
				<div class="shadow-box">
					<div class="comments">
						<?php if (count($entry->comments)) : ?>
							<ul>
								<?php foreach ($entry->comments as $comment) : ?>
									<?php $this->renderPartial('/photoComment/_listcomment', array(
										'entry' => $comment,
									)); ?>
								<?php endforeach; ?>	
							</ul>
						<?php else:?>
							<div class="empty-list">No Comments</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>
		<section class="box">
			<div class="box-title">
				<h3>Add Comment</h3>
			</div>
			<div class="box-content">
				<div class="shadow-box">
					<div class="standart-form form">
						<?php $form=$this->beginWidget('YsaMemberForm', array(
								'action' => YsaHtml::normalizeUrl( array('photo/comment/'.$entry->id) ),
								'id'=>'photo-comment-form',
						)); ?>
						<section class="cf">
							<?php echo $form->labelEx($entryComment,'comment'); ?>
							<div>
								<?php echo $form->textArea($entryComment,'comment', array('cols' => 40, 'rows' => 5)); ?>
								<?php echo $form->error($entryComment,'comment'); ?>
							</div>
						</section>
						<div class="button">
							<?php echo YsaHtml::submitButton('Add Comment'); ?>
						</div>
						<?php $this->endWidget(); ?>
					</div>
				</div>
			</div>
		</section>
	<?php endif ?>
	<?/*
	<h3>Order sizes for photo</h3>
	<?php $this->beginWidget('YsaMemberForm', array(
			'id'=>'photo-size-form',
			'action' => array('photo/saveSizes/' . $entry->id),
	)); ?>
	<ul>
		<?php echo YsaHtml::checkBoxList(
			'PhotoSizes', 
			YsaHtml::listData($entry->sizes, 'id', 'id'),
			YsaHtml::listData($photoSizes, 'id', 'title'), 
			array(
				'template'  => '<li>{input} {label}</li>',
				'separator' => '',
			)
		); ?>
	</ul>
	 
	<p>Album default sizes: <?php foreach ($entry->album->sizes as $k => $size) : ?>
			<strong><?php echo $size->title; ?></strong>
		<?php endforeach; ?></p>

	<?php echo YsaHtml::submitButton('Save'); ?>
	<?php $this->endWidget();?>
	*/?>

</div>