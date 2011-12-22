<section id="studio">
	<?php echo YsaHtml::pageHeaderTitle('Studio Information'); ?>

	<div class="w">
		<h3>General Information</h3>
		<?php $this->renderPartial('_form', array(
			'entry' => $entry,
		)); ?>
		
		<h3>Specials</h3>
		<div id="studio-specials">
			<?php $this->renderPartial('_specialsForm', array(
				'entry' => $specials,
			)); ?>
		</div>
		
		<h3>Photographer Information</h3>
		
		<div id="studio-photographer-info">
			<?php echo YsaHtml::link('Add Photographer', array('person/add')); ?>
			
			<ul>
				<?php foreach ($this->member()->studio()->persons() as $person) : ?>
					<?php $this->renderPartial('/person/_listperson', array(
						'entry' => $person,
					)); ?>
				<?php endforeach; ?>
			</ul>
			
		</div>
		
		
		
		
		<h3>Links</h3>
		<section id="studio-links">
			<ul>
				<?php foreach ($this->member()->studio()->links() as $link) : ?>
					<?php $this->renderPartial('/link/_listlink', array(
						'entry' => $link,
					)); ?>
				<?php endforeach; ?>
			</ul>
			
			<?php $this->renderPartial('/link/_form', array(
				'entry' => $entryLink,
			)); ?>
		</section>

		
	</div>
</section>