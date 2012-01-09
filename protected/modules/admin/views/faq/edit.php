<div class="g12">
	<?php echo $this->renderPartial('/_messages/save');?>
	<div class="form">
		<?php $form=$this->beginWidget('YsaAdminForm', array(
			'id'=>'option-group-form',
			'enableAjaxValidation'=>false,
		)); ?>
		<fieldset>
			<label>General Information</label>
			<section>
				<?php echo $form->labelEx($entry,'question'); ?>
				<div>
					<?php echo $form->textArea($entry,'question', array('size'=>60,'maxlength'=>200)); ?>
					<?php echo $form->error($entry,'question'); ?>
				</div>
			</section>
			<section>
				<?php echo $form->labelEx($entry,'answer'); ?>
				<div>
					<?php echo $form->textArea($entry,'answer', array(
						'class' => 'html',
						'rows'  => 12,
					)); ?>
					<?php echo $form->error($entry,'answer'); ?>
				</div>
			</section>
			<section>
				<?php echo $form->labelEx($entry,'state'); ?>
				<div>
					<?php echo $form->checkBox($entry,'state', array(
						'checked' => $entry->state,
					)); ?>
					<?php echo $form->error($entry,'state'); ?>
				</div>
			</section>
		</fieldset>
		<?php echo YsaHtml::adminSaveSection();?>
		<?php $this->endWidget(); ?>
	</div>
</div>