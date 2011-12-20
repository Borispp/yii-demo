<section id="studio">
	<?php echo YsaHtml::pageHeaderTitle('Studio Information'); ?>

	<div class="w">
		<h3>General Information</h3>
		
		<div class="form">
			<?php $form=$this->beginWidget('YsaMemberForm', array(
					'id'=>'studio-info-form',
					'enableAjaxValidation'=>false,
			)); ?>
			<section>
				<?php echo $form->labelEx($entry,'name'); ?>
				<div>
					<?php echo $form->textField($entry,'name', array('maxlength' => 100)); ?>
					<?php echo $form->error($entry,'name'); ?>
				</div>
			</section>
			<section>
				<?php echo $form->labelEx($entry,'blog_feed'); ?>
				<div>
					<?php echo $form->textField($entry,'blog_feed', array('maxlength' => 100)); ?>
					<?php echo $form->error($entry,'blog_feed'); ?>
				</div>
			</section>
			<section>
				<?php echo $form->labelEx($entry,'twitter_feed'); ?>
				<div>
					<?php echo $form->textField($entry,'twitter_feed', array('maxlength' => 100)); ?>
					<?php echo $form->error($entry,'twitter_feed'); ?>
				</div>
			</section>
			
			<section>
				<?php echo $form->labelEx($entry,'facebook_feed'); ?>
				<div>
					<?php echo $form->textField($entry,'facebook_feed', array('maxlength' => 100)); ?>
					<?php echo $form->error($entry,'facebook_feed'); ?>
				</div>
			</section>
			
			<section class="button">
				<?php echo YsaHtml::submitButton('Save'); ?>
			</section>
			<?php $this->endWidget(); ?>
		</div>
		
		<h3>Photographer Information</h3>
		
		<div id="studio-photographer-info">
			<?php echo YsaHtml::link('Add Photographer', array('person/add')); ?>
		</div>
		
		<h3>Links</h3>
		<section id="studio-links">
			<ul>
				<?php foreach ($this->member()->studio()->links() as $link) : ?>
					<?php $this->renderPartial('_listlink', array(
						'entry' => $link,
					)); ?>
				<?php endforeach; ?>
			</ul>
			
			<?php $this->renderPartial('_editlinkForm', array(
				'entry' => $entryLink,
			)); ?>
		</section>

		
	</div>
</section>