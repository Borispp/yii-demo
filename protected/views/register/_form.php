<?php //$this->widget('ext.eauth.EAuthWidget', array('action' => 'register/oauth')) ?>

<div class="form large-form">
	<?php $form=$this->beginWidget('YsaForm', array(
			'id'=>'registration-form',
	)); ?>
	<?php echo $form->errorSummary($model, false); ?>
	<section>
		<?php echo $form->emailField($model,'email', array('placeholder' => 'Email')); ?>
	</section>

	<section>
		<?php echo $form->passwordField($model,'password', array('placeholder' => 'Password')); ?>
	</section>

	<section>
		<?php echo $form->passwordField($model,'verifyPassword', array('placeholder' => 'Verify Password')); ?>
	</section>

	<section>
		<?php echo $form->textField($model,'first_name', array('placeholder' => 'First Name')); ?>
	</section>

	<section>
		<?php echo $form->textField($model,'last_name', array('placeholder' => 'Last Name')); ?>
	</section>
	
<?/*
	<section>
		<?php echo $form->labelEx($model,'verifyCode'); ?>
		<?php $this->widget('CCaptcha'); ?>
		<?php echo $form->textField($model,'verifyCode', array('autocomplete' => 'off')); ?>
		<?php echo $form->error($model,'verifyCode'); ?>
	</div>
 */?>
	<section class="buttons">
		<div class="subscribe"><?php echo $form->checkBox($model,'subscribe'); ?><?php echo $form->labelEx($model,'subscribe'); ?></div>
		<?php echo YsaHtml::submitButton('Register', array('class' => 'blue')); ?>
	</section>

	<?php $this->endWidget(); ?>

</div><!-- form -->