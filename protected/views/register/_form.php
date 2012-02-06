<?php //$this->widget('ext.eauth.EAuthWidget', array('action' => 'register/oauth')) ?>

<div class="form large-form">
	<?php $form=$this->beginWidget('YsaForm', array(
			'id'=>'registration-form',
	)); ?>
	<?php echo $form->errorSummary($model, false); ?>
	<section>
<<<<<<< HEAD
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
=======
		<?php echo $form->emailField($model,'email', array('placeholder' => 'Email', 'required' => 'required')); ?>
	</section>

	<section>
		<?php echo $form->passwordField($model,'password', array('placeholder' => 'Password', 'required' => 'required')); ?>
	</section>

	<section>
		<?php echo $form->passwordField($model,'verifyPassword', array('placeholder' => 'Verify Password', 'required' => 'required')); ?>
	</section>

	<section>
		<?php echo $form->textField($model,'first_name', array('placeholder' => 'First Name', 'required' => 'required')); ?>
	</section>

	<section>
		<?php echo $form->textField($model,'last_name', array('placeholder' => 'Last Name', 'required' => 'required')); ?>
>>>>>>> d827d60c5729572833ecbe8a13231818268a1940
	</section>
	
<?/*
	<section>
		<?php echo $form->labelEx($model,'verifyCode'); ?>
		<?php $this->widget('CCaptcha'); ?>
		<?php echo $form->textField($model,'verifyCode', array('autocomplete' => 'off')); ?>
		<?php echo $form->error($model,'verifyCode'); ?>
	</div>
 */?>
<<<<<<< HEAD
	<section class="buttons">
		<div class="subscribe"><?php echo $form->checkBox($model,'subscribe'); ?><?php echo $form->labelEx($model,'subscribe'); ?></div>
		<?php echo YsaHtml::submitButton('Register', array('class' => 'blue')); ?>
=======
	<section class="buttons cf">
		<div class="subscribe"><?php echo $form->checkBox($model,'subscribe'); ?><?php echo $form->labelEx($model,'subscribe'); ?></div>
		<?php echo YsaHtml::submitLoadingButton('Register', array('class' => 'blue')); ?>
>>>>>>> d827d60c5729572833ecbe8a13231818268a1940
	</section>

	<?php $this->endWidget(); ?>

<<<<<<< HEAD
=======
	
	<?php $this->widget('ext.eauth.EAuthWidget', array('action' => 'auth/oauthRegistration')) ?>
	
>>>>>>> d827d60c5729572833ecbe8a13231818268a1940
</div><!-- form -->