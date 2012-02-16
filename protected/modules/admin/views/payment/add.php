<div class="g12">
	<div class="form">
		<?php $form=$this->beginWidget('YsaAdminForm', array(
			'id'=>'option-group-form',
			'enableAjaxValidation'=>false,
		)); ?>
		<fieldset>
			<label>General Information</label>
			<section>
				<?php echo $form->labelEx($entry,'name*'); ?>
				<div>
					<?php echo $form->textField($entry,'name', array('size'=>60,'maxlength'=>50, 'class' => 'w_50')); ?>
					<?php echo $form->error($entry,'name'); ?>
				</div>
			</section>
			<section>
				<?php echo $form->labelEx($entry,'summ*'); ?>
				<div>
					<?php echo $form->textField($entry,'summ', array('size'=>12,'maxlength'=>12, 'class' => 'w_20')); ?> $
					<?php echo $form->error($entry,'summ'); ?>
				</div>
			</section>
			<section>
				<?php echo $form->labelEx($entry,'description'); ?>
				<div>
					<?php echo $form->textArea($entry,'description', array(
						'data-autogrow' => 'true',
						'rows'          => 3,
					)); ?>
					<?php echo $form->error($entry,'description'); ?>
				</div>
			</section>
			<section>
				<?php echo $form->labelEx($entry,'notes'); ?>
				<div>
					<?php echo $form->textArea($entry,'notes', array(
						'data-autogrow' => 'true',
						'rows'          => 3,
					)); ?>
					<?php echo $form->error($entry,'notes'); ?>
				</div>
			</section>
			<section>
				<?php echo $form->labelEx($entry,'state'); ?>
				<div>
					<?php echo $form->dropDownList($entry,'state', $entry->getStates()); ?>
					<?php echo $form->error($entry,'state'); ?>
				</div>
			</section>
			<section>
				<?php echo $form->labelEx($entry,'type*'); ?>
				<div>
					<?php echo $form->dropDownList($entry,'type', array(
						'application'  => 'Initial Application Payment',
//						'subscription' => 'Subscription',
					)); ?>
					<?php echo $form->error($entry,'type'); ?>
				</div>
			</section>
			<section>
				<?php echo YsaHtml::label('Application', 'application_id')?>
				<div>
					<?php echo YsaHtml::dropDownList('application_id', NULL, CHtml::listData($applicationList, 'id' , 'name')); ?>
				</div>
			</section>
		</fieldset>
		<?php echo YsaHtml::adminSaveSection();?>
		<?php $this->endWidget(); ?>
	</div>
</div>