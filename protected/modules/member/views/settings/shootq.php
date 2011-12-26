<section class="body w">
<h3>ShootQ Settings</h3>

<?php $form = $this->beginWidget('YsaForm', array(
	'id'=>'shootq-form',
	'enableAjaxValidation'=>false,
)); ?>

<section>
	<?php echo $form->labelEx($shootq,'shootq_enabled'); ?>
	<?php echo $form->checkBox($shootq,'shootq_enabled',array('checked' => $shootq->shootq_enabled)); ?>
	<div>
		<?php echo $form->error($shootq,'shootq_enabled'); ?>
	</div>
</section>

<section>
	<?php echo $form->labelEx($shootq,'shootq_abbr'); ?>
	<div>
		<?php echo $form->textField($shootq,'shootq_abbr', array('size'=>60,'maxlength'=>50)); ?>
		<?php echo $form->error($shootq,'shootq_abbr'); ?>
	</div>
</section>

<section>
	<?php echo $form->labelEx($shootq,'shootq_key'); ?>
	<div>
		<?php echo $form->textField($shootq,'shootq_key',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($shootq,'shootq_key'); ?>
	</div>
</section>

<div class="row buttons">
	<?php echo CHtml::submitButton('Submit'); ?>
</div>

<?php $this->endWidget();?>

</section>
