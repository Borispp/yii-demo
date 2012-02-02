<div class="w body">
	<?php echo $page->content; ?>
	
	<div class="form">
		<?php $form=$this->beginWidget('YsaForm', array(
				'id'=>'contact-form',
		)); ?>

		<section>
			<?php echo $form->labelEx($entry,'name'); ?>
			<div>
				<?php echo $form->textField($entry,'name'); ?>
				<?php echo $form->error($entry,'name'); ?>
			</div>
		</section>
		
		<section>
			<?php echo $form->labelEx($entry,'email'); ?>
			<div>
				<?php echo $form->textField($entry,'email'); ?>
				<?php echo $form->error($entry,'email'); ?>
			</div>
		</section>

		<section>
			<?php echo $form->labelEx($entry,'studio_name'); ?>
			<div>
				<?php echo $form->textField($entry,'studio_name'); ?>
				<?php echo $form->error($entry,'studio_name'); ?>
			</div>
		</section>
		
		<section>
			<?php echo $form->labelEx($entry,'studio_website'); ?>
			<div>
				<?php echo $form->textField($entry,'studio_website'); ?>
				<?php echo $form->error($entry,'studio_website'); ?>
			</div>
		</section>
		
		<section>
			<?php echo $form->labelEx($entry,'phone_number'); ?>
			<div>
				<?php echo $form->textField($entry,'phone_number'); ?>
				<?php echo $form->error($entry,'phone_number'); ?>
			</div>
		</section>
		
		<section>
			<?php echo $form->labelEx($entry,'message'); ?>
			<div>
				<?php echo $form->textArea($entry,'message', array('cols' => 40, 'rows' => 5)); ?>
				<?php echo $form->error($entry,'message'); ?>
			</div>
		</section>
		
        <?php if(extension_loaded('gd')): ?>
        <section>
			<?php echo $form->labelEx($entry, 'captcha'); ?>
			<div>
				<?php $this->widget('CCaptcha'); ?>
				<?php echo $form->textField($entry, 'captcha'); ?>
			</div>
			<div class="hint">Please enter the letters as they are shown in the image above.
			<br/>Letters are not case-sensitive.</div>
        </section>
        <?php endif; ?>
		
		

		<section class="button">
			<?php echo CHtml::submitButton('Let Us Know'); ?>
		</section>

		<?php $this->endWidget(); ?>
	</div>
</div>