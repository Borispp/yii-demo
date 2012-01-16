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
				<?php echo YsaHtml::link('Add Photographer', array('person/add'), array('class' => 'btn blue')); ?>
			</div>
		</div>
		<div class="box-content">
			<div class="shadow-box">
				<?php if (count($this->member()->studio->persons)) : ?>
					<ul>
						<?php foreach ($this->member()->studio->persons as $person) : ?>
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
	
	<section class="box" id="studio-links">
		<div class="box-title">
			<h3>Links</h3>
		</div>
		<div class="box-content">
			<ul>
				<?php foreach ($this->member()->studio->links as $link) : ?>
					<?php $this->renderPartial('/link/_listlink', array(
						'entry' => $link,
					)); ?>
				<?php endforeach; ?>
			</ul>
			<?php $this->renderPartial('/link/_form', array(
				'entry' => $entryLink,
			)); ?>
		</div>
	</section>
	
		<?/*
		<h3>Splash</h3>
		<div id="studio-splash">
			<?php $this->renderPartial('_splashForm', array(
				'entry' => $splash,
			)); ?>
		</div>
		 */?>
</section>