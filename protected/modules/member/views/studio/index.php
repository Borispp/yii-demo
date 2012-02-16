<section id="studio" class="w">

	
	<section class="box">
		<div class="box-title">
			<h3>Contact Information</h3>
			<?php if ($entry->help('contact')) : ?>
				<a href="<?php echo $entry->help('contact');?>" class="box-help fancybox">?</a>
			<?php endif; ?>
		</div>
		<div class="box-content">
			<div class="shadow-box" id="studio-contacts">
				<?php $this->renderPartial('_contactForm', array(
					'entry' => $contactForm,
				)); ?>
			</div>
		</div>
	</section>
	
	<section class="box">
		<div class="box-title">
			<h3>Blog and Social Media Information</h3>
			<?php if ($entry->help('general')) : ?>
				<a href="<?php echo $entry->help('general');?>" class="box-help fancybox">?</a>
			<?php endif; ?>
		</div>
		<div class="box-content">
			<?php $this->renderPartial('_form', array(
				'entry' => $entry,
			)); ?>
		</div>
	</section>
	
	<section class="box">
		<div class="box-title">
			<h3>Specials/Custom Page</h3>
			<?php if ($entry->help('specials')) : ?>
				<a href="<?php echo $entry->help('specials');?>" class="box-help fancybox">?</a>
			<?php endif; ?>
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
			<?php if ($entry->help('shooters')) : ?>
				<a href="<?php echo $entry->help('shooters');?>" class="box-help fancybox">?</a>
			<?php endif; ?>
			<div class="box-title-button">
				<?php echo YsaHtml::link('<span class="icon i_round_plus"></span>Add Photographer', array('person/add'), array('class' => 'secondary iconed fancybox.ajax', 'id' => 'studio-person-add-button')); ?>
			</div>
		</div>
		<div class="box-content">
			<div class="shadow-box">
				
				<div class="box-description">
					Create unique profiles for all of your studio photographers to be seen on the Studio page within your app. Each profile photo should be 100x100 pixels.
				</div>
				
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
			<?php if ($entry->help('custom')) : ?>
				<a href="<?php echo $entry->help('custom');?>" class="box-help fancybox">?</a>
			<?php endif; ?>
			<?php if (count($entry->customLinks) < 2) : ?>
				<div class="box-title-button">
					<?php echo YsaHtml::link('<span class="icon i_round_plus"></span>Add Custom Link', array('link/addCustom'), array('class' => 'secondary iconed fancybox.ajax', 'id' => 'studio-link-custom-add-button')); ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="box-content">
			<div class="shadow-box">
				<div class="box-description">These links are shown under the “more” tab in your application.</div>
				<?php if (count($entry->customLinks)) : ?>
				
					<?php
						$folder = $this->member()->application->option('style');
						if (!$folder) {
							$folder = 'black';
						}
					?>
				
					<ul class="list links cf" data-type="<?php echo StudioLink::TYPE_BOOKMARK?>">
						<?php foreach ($entry->customLinks as $link) : ?>
							<?php $this->renderPartial('/link/_listcustom', array(
								'entry'		=> $link,
								'folder'	=> $folder,
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
			<h3>Studio Page Featured Links</h3>
			<?php if ($entry->help('bookmarks')) : ?>
				<a href="<?php echo $entry->help('bookmarks');?>" class="box-help fancybox">?</a>
			<?php endif; ?>
			<div class="box-title-button">
				<?php echo YsaHtml::link('<span class="icon i_round_plus"></span>Add Bookmark', array('link/addBookmark'), array('class' => 'secondary iconed fancybox.ajax', 'id' => 'studio-link-bookmark-add-button')); ?>
			</div>			
		</div>
		<div class="box-content">
			<div class="shadow-box">
				
				<div class="box-description">
					You can add up to 10 of links to display on your Studio page. Maximum amount of 50 characters per line.
				</div>
				
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
			<?php if ($entry->help('video')) : ?>
				<a href="<?php echo $entry->help('video');?>" class="box-help fancybox">?</a>
			<?php endif; ?>
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