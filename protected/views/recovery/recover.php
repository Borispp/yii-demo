<div class="general-page" id="recover">
	<div class="content cf">
		<?php if(Yii::app()->user->hasFlash('recoveryMessage')): ?>
			<div class="success">
				<?php echo Yii::app()->user->getFlash('recoveryMessage'); ?>
			</div>
		<?php else: ?>
			<div class="form large-form">
				<?php echo YsaHtml::errorSummary($entry, false, false); ?>
				<?php $form=$this->beginWidget('YsaForm', array(
						'id'=>'recovery-change-password-form',
				)); ?>
				<section class="cf">
					<div>
						<?php echo $form->textField($entry,'email', array('placeholder' => 'Please enter your email here')); ?>
					</div>
				</section>
				<section class="buttons">
					<?php echo YsaHtml::submitLoadingButton('Restore', array('class' => 'blue')); ?>
				</section>
				<?php $this->endWidget(); ?>
			</div>
			<div class="info">
				<p>Please enter your username or email to reset your password. You’ll receive an email with instructions. </p>
			</div>
		<?php endif; ?>
	</div>

</div>