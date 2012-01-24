<section id="studio" class="w">
	<section class="box">
		<div class="box-title">
			<h3>General Information</h3>
		</div>
		<div class="box-content">
			<?php $this->renderPartial('_form', array(
				'entry' => $entry,
			)); ?>
		</div>
	</section>
	
	<section class="box">
		<div class="box-title">
			<h3>Specials</h3>
		</div>
		<div class="box-content">
			<div class="shadow-box" id="studio-specials">
				<?php $this->renderPartial($entry->specials ? '_specialsPhoto' : '_specialsUpload', array(
					'entry' => $entry,
				)); ?>
			</div>
		</div>
	</section>
		
	<section class="box" id="studio-photographer-info">
		<div class="box-title">
			<h3>Studio Photographers</h3>
			<div class="box-title-button">
				<?php echo YsaHtml::link('<span class="icon i_plus_alt"></span>Add Photographer', array('person/add'), array('class' => 'secondary iconed fancybox.ajax', 'id' => 'studio-person-add-button')); ?>
			</div>
		</div>
		<div class="box-content">
			<div class="shadow-box">
				<?php if (count($entry->persons)) : ?>
					<ul class="list persons cf">
						<?php foreach ($entry->persons as $person) : ?>
							<?php $this->renderPartial('/person/_listperson', array(
								'entry' => $person,
							)); ?>
						<?php endforeach; ?>
					</ul>
				<?php else:?>
				<div class="empty-list">Empty List</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	
	<section class="box" id="studio-icon-links">
		<div class="box-title">
			<h3>Custom Links</h3>
			<?php if (count($entry->customLinks) < 2) : ?>
				<div class="box-title-button">
					<?php echo YsaHtml::link('<span class="icon i_plus_alt"></span>Add Custom Link', array('link/addCustom'), array('class' => 'secondary iconed fancybox.ajax', 'id' => 'studio-link-custom-add-button')); ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="box-content">
			<div class="shadow-box">
				<div class="box-description">These links are shown under "more" tab in your Application</div>
				<?php if (count($entry->customLinks)) : ?>
					<ul class="list links cf" data-type="<?php echo StudioLink::TYPE_BOOKMARK?>">
						<?php foreach ($entry->customLinks as $link) : ?>
							<?php $this->renderPartial('/link/_listcustom', array(
								'entry' => $link,
							)); ?>
						<?php endforeach; ?>
					</ul>
				<?php else:?>
					<div class="empty-list">Empty List</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	
	<section class="box" id="studio-bookmark-links">
		<div class="box-title">
			<h3>Bookmarks</h3>
			<div class="box-title-button">
				<?php echo YsaHtml::link('<span class="icon i_plus_alt"></span>Add Bookmark', array('link/addBookmark'), array('class' => 'secondary iconed fancybox.ajax', 'id' => 'studio-link-bookmark-add-button')); ?>
			</div>			
		</div>
		<div class="box-content">
			<div class="shadow-box">
				<?php if (count($entry->bookmarkLinks)) : ?>
					<ul class="list links cf" data-type="<?php echo StudioLink::TYPE_BOOKMARK?>">
						<?php foreach ($entry->bookmarkLinks as $link) : ?>
							<?php $this->renderPartial('/link/_listbookmark', array(
								'entry' => $link,
							)); ?>
						<?php endforeach; ?>
					</ul>
				<?php else:?>
					<div class="empty-list">Empty List</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	
	<section class="box">
		<div class="box-title">
			<h3>Video</h3>
		</div>
		<div class="box-content">
			<div class="shadow-box">
				<div class="box-description">
					If you have your own video you can upload it on YouTube or Vimeo and insert a link here.
				</div>
				<div id="studio-video" class="cf">
					<?php if ($entry->video) : ?>
						<?php $this->renderPartial('_video', array(
							'entry' => $entry,
						)); ?>
					<?php else:?>
						<?php $this->renderPartial('_videoForm', array(
							'entry' => $videoForm,
						)); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
</section>