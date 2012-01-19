<div class="w">
	
	<section class="box">
		<div class="box-title">
			<h3>ShootQ Settings</h3>
		</div>
		<div class="box-content">
			<div class="shadow-box">
				<div class="form standart-form">
					<?php $form = $this->beginWidget('YsaForm', array(
						'id'=>'shootq-form',
						'enableAjaxValidation'=>false,
					)); ?>

					<section class="cf">
						<?php echo $form->labelEx($shootq,'shootq_enabled'); ?>
						<div>
							<?php echo $form->checkBox($shootq,'shootq_enabled',array('checked' => $shootq->shootq_enabled)); ?>
							<?php echo $form->error($shootq,'shootq_enabled'); ?>
						</div>
					</section>

					<section class="cf">
						<?php echo $form->labelEx($shootq,'shootq_abbr'); ?>
						<div>
							<?php echo $form->textField($shootq,'shootq_abbr', array('size'=>60,'maxlength'=>50)); ?>
							<?php echo $form->error($shootq,'shootq_abbr'); ?>
						</div>
					</section>

					<section class="cf">
						<?php echo $form->labelEx($shootq,'shootq_key'); ?>
						<div>
							<?php echo $form->textField($shootq,'shootq_key',array('size'=>50,'maxlength'=>50)); ?>
							<?php echo $form->error($shootq,'shootq_key'); ?>
						</div>
					</section>
					<div class="button">
						<?php echo YsaHtml::submitButton('Submit', array('class' => 'blue')); ?>
					</div>
					<?php $this->endWidget();?>
				</div>
			</div>
		</div>
	</section>
</div>
