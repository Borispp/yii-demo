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
	
	<section class="box" id="studio-links">
		<div class="box-title">
			<h3>Links</h3>
			<?php if (count($this->member()->studio->links) < 2) : ?>
				<div class="box-title-button">
					<?php echo YsaHtml::link('<span class="icon i_plus_alt"></span>Add Link', array('link/add'), array('class' => 'secondary iconed fancybox.ajax', 'id' => 'studio-link-add-button')); ?>
				</div>
			<?php endif; ?>
			
		</div>
		<div class="box-content">
			<div class="shadow-box">
				<?php if (count($entry->links)) : ?>
					<ul class="list links cf">
						<?php foreach ($entry->links as $link) : ?>
							<?php $this->renderPartial('/link/_listlink', array(
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
</section>