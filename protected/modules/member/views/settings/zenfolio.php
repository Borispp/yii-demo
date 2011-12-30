<div class="w">
	<h2>ZenFolio Integration</h2>
	
	<?php if ($this->member()->zenfolioAuthorized()) : ?>

	<?php else:?>
	
		<?php $form = $this->beginWidget('YsaForm', array(
			'id'=>'zenfolio-form',
			'enableAjaxValidation'=>false,
		)); ?>

		<section>
			<?php echo $form->labelEx($zenlogin,'username'); ?>
			<div>
				<?php echo $form->textField($zenlogin,'username', array('size'=>50,'maxlength'=>50)); ?>
				<?php echo $form->error($zenlogin,'username'); ?>
			</div>
		</section>

		<section>
			<?php echo $form->labelEx($zenlogin,'password'); ?>
			<div>
				<?php echo $form->textField($zenlogin,'password',array('size'=>50,'maxlength'=>50)); ?>
				<?php echo $form->error($zenlogin,'password'); ?>
			</div>
		</section>

		<div class="row buttons">
			<?php echo CHtml::submitButton('Authorize'); ?>
		</div>

		<?php $this->endWidget();?>
	
	<?php endif; ?>
	
	
</div>