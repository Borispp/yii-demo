<div class="form">
	<?php $form=$this->beginWidget('YsaForm', array(
		'id'=>'login-window-form',
		'action' => array('auth/login/')
	)); ?>
	<div class="rows">
		<div class="row">
			<?php echo $form->emailField($model,'email', array('placeholder' => 'Email')); ?>
			<span class="ico-name"></span>
		</div>
		<div class="row">
			<?php echo $form->passwordField($model,'password', array('placeholder' => 'Password')); ?>
			<span class="ico-pass"></span>
		</div>


		<div class="row cf">
			<div class="remember">
				<?php echo $form->checkBox($model,'rememberMe'); ?>
				<?php echo $form->label($model,'rememberMe'); ?>
			</div>
			<?php echo YsaHtml::submitButton('Login', array('class' => 'blue')); ?>
		</div>
	</div>
	
	<div class="links cf">
		<?php echo YsaHtml::link('Register', array('register/'), array('class' => 'reg')); ?><span>|</span><?php echo YsaHtml::link("Lost Password?", array('recovery/'), array('class' => 'lost')); ?>
	</div>
<?php $this->endWidget(); ?>
</div><!-- form -->