<div class="w" id="photo">
	<figure>
		<?php echo $entry->full(); ?>
	</figure>

	<?php if ( !$entry->album->event->isPortfolio() ) : ?>
	<h3>Comments</h3>
	<ul>
		<?php foreach ($entry->comments as $comment) : ?>
			<?php $this->renderPartial('/photoComment/_listcomment', array(
				'entry' => $comment,
			)); ?>
		<?php endforeach; ?>	
	</ul>

	<div class="form">
		<?php $form=$this->beginWidget('YsaMemberForm', array(
				'action' => Chtml::normalizeUrl( array('photo/comment/'.$entry->id) ),
				'id'=>'photo-comment-form',
				'enableAjaxValidation'=>false,
		)); ?>
		<section>
			<?php echo $form->labelEx($entryComment,'comment'); ?>
			<div>
				<?php echo $form->textArea($entryComment,'comment', array('cols' => 40, 'rows' => 5)); ?>
				<?php echo $form->error($entryComment,'comment'); ?>
			</div>
		</section>

		<section class="button">
			<?php if ( $member->hasFacebook() && $entry->canShareComments() ) : ?>
				<?php $checkbox_options = isset($_POST['EventPhotoComment']['forward2facebook']) && empty($_POST['EventPhotoComment']['forward2facebook']) ? null : array('checked'=>'checked') ?>
				<?php echo $form->checkbox($entryComment,'forward2facebook', $checkbox_options); ?>
				<?php echo $form->labelEx($entryComment,'forward2facebook'); ?>
			<?php endif ?>
			<?php echo YsaHtml::submitButton('Add Comment'); ?>
		</section>
		<?php $this->endWidget(); ?>
	</div>
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

	<h3>Photo Rating: <?php echo $entry->rating(); ?></h3>

	<?php if (!$entry->album->event->isProofing()) : ?>

		<h3>Availability for sharing/order this photo</h3>
		<?php $avlForm = $this->beginWidget('YsaMemberForm', array(
				'id'=>'photo-availability-form',
				'action' => array('photo/saveAvailability/' . $entry->id),
		)); ?>
		<p>
			<?php echo $avlForm->labelEx($availability,'can_order'); ?>
			<?php echo $avlForm->checkBox($availability, 'can_order', array('checked' => $entry->canOrder(), 'disabled' => !$entry->album->canOrder())); ?>
		</p>
		<p>
			<?php echo $avlForm->labelEx($availability,'can_share'); ?>
			<?php echo $avlForm->checkBox($availability, 'can_share', array('checked' => $entry->canShare(), 'disabled' => !$entry->album->canShare())); ?>
		</p>

		<p><?php echo YsaHtml::submitButton('Save'); ?></p>

		<p>Album photo order: <?php echo $entry->album->canOrder() ? 'Yes' : 'No'; ?></p>
		<p>Album photo sharing: <?php echo $entry->album->canShare() ? 'Yes' : 'No'; ?></p>

		<?php $this->endWidget();?>

		<p><?php echo $entry->shareLink(); ?></p>

	<?php endif;?>
</div>