<div class="general-page" id="login-register">
	<div class="content cf">
		
		<?php if ($page) : ?>
			<div class="page">
				<?php echo $page->body; ?>
			</div>
		<?php endif; ?>
		
		
		<div class="login">
			<div class="form large-form">
				<h2><?php echo Yii::t('general', 'Login'); ?></h2>
				<?php //$this->widget('ext.eauth.EAuthWidget', array('action' => 'auth/loginoauth')) ?>
				<?php $form=$this->beginWidget('YsaForm', array(
					'id'=>'login-form',
				)); ?>
				<?php echo YsaHtml::errorSummary($login, false, false); ?>
				<section>
					<?php echo $form->textField($login,'email', array('placeholder' => 'Email')); ?>
				</section>
				<section>
					<?php echo $form->passwordField($login,'password', array('placeholder' => 'Password')); ?>
				</section>
				<section class="buttons cf">
					<p><?php echo YsaHtml::link("Lost Password?", array('recovery/')); ?></p>
					<?php echo YsaHtml::submitButton('Login', array('class' => 'blue')); ?>
					<div class="remember"><?php echo $form->labelEx($login,'rememberMe'); ?><?php echo $form->checkBox($login,'rememberMe'); ?></div>
				</section>
			<?php $this->endWidget(); ?>
			</div>
		</div>

		<div class="register">
			<h2><?php echo Yii::t('general', 'Create an account'); ?></h2>
			
			<?php $this->renderPartial('/register/_form', array(
				'model' => $register,
			))?>
		</div>
	</div>
	

	


</div> 