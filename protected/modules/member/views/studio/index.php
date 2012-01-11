<section id="studio" class="w">
		<h3>General Information</h3>
		<?php $this->renderPartial('_form', array(
			'entry' => $entry,
		)); ?>
		
		<div id="studio-specials">
			<h3>Specials</h3>
			<?php $this->renderPartial($entry->specials ? '_specialsPhoto' : '_specialsUpload', array(
				'entry' => $entry,
			)); ?>
		</div>

		<h3>Photographer Information</h3>
		
		<div id="studio-photographer-info">
			<?php echo YsaHtml::link('Add Photographer', array('person/add')); ?>
			
			<ul>
				<?php foreach ($this->member()->studio->persons as $person) : ?>
					<?php $this->renderPartial('/person/_listperson', array(
						'entry' => $person,
					)); ?>
				<?php endforeach; ?>
			</ul>
		</div>
		
		<h3>Links</h3>
		<section id="studio-links">
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