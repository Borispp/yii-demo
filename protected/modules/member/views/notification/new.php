<div class="w">
	<div class="form">
	<?php $form=$this->beginWidget('YsaMemberForm', array(
		'id'=>'notification-form',
		'enableAjaxValidation'=>false,
	)); ?>
	<section>
		<?php echo $form->labelEx($entry,'event_id'); ?>
		<div>
			<?php echo $form->dropDownList($entry, 'event_id', CHtml::listData($events, 'id', 'name')) ?>
			<?php echo $form->error($entry,'state'); ?>
		</div>
	</section>
	<section>
		<?php echo $form->labelEx($entry,'message'); ?>
		<div>
			<?php echo $form->textArea($entry,'message', array('cols' => 40, 'rows' => 4)); ?>
			<?php echo $form->error($entry,'message'); ?>
		</div>
	</section>
	<section class="button">
		<?php echo YsaHtml::submitButton($entry->isNewRecord ? 'Add' : 'Save'); ?>
	</section>
	<?php $this->endWidget(); ?>
</div>
</div>