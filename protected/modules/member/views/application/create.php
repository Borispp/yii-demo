<div class="w">
	<section class="box">
		<div class="box-title">
			<h3>Create Application</h3>
		</div>
		<div class="box-content">
			<div class="shadow-box">
				<div class="form standart-form">
					<?php $form=$this->beginWidget('YsaMemberForm', array(
							'id'=>'create-app-form',
							'enableAjaxValidation'=>false,
					)); ?>
					<section class="cf">
						<?php echo $form->labelEx($app,'name'); ?>
						<div>
							<?php echo $form->textField($app,'name', array('maxlength' => 100)); ?>
							<?php echo $form->error($app,'name'); ?>
						</div>
					</section>
					<section class="cf">
						<?php echo $form->labelEx($app,'info'); ?>
						<div>
							<?php echo $form->textArea($app,'info', array('cols' => 40, 'rows' => 4)); ?>
							<?php echo $form->error($app,'info'); ?>
						</div>
					</section>
					<div class="button">
						<?php echo YsaHtml::submitButton('Create', array('class' => 'blue')); ?>
					</div>
					<?php $this->endWidget(); ?>
				</div>
			</div>
		</div>
	</section>
</div>


