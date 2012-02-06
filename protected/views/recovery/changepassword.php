<div class="general-page" id="recovery-change-password">
	<div class="content cf">
		<?php if(Yii::app()->user->hasFlash('recoveryMessage')): ?>
			<div id="recover-success">
				<span class="icon lock"></span>
				<div class="message">
					<?php echo Yii::app()->user->getFlash('recoveryMessage'); ?>
					<div class="buttons c"><?php echo YsaHtml::link('Login Now', array('login/'), array('class' => 'btn blue')); ?></div>
				</div>
			</div>
		<?php else: ?>
			<div class="form large-form">
				
				<div class="info">
					<?php echo $page->content; ?>
				</div>

				<?php echo YsaHtml::errorSummary($entry, false, false); ?>
				<?php $form=$this->beginWidget('YsaForm', array(
						'id'=>'recovery-change-password-form',
				)); ?>
				<section>
					<div>
						<?php echo $form->passwordField($entry,'password', array('placeholder' => 'Password')); ?>
					</div>
				</section>
				<section>
					<div>
						<?php echo $form->passwordField($entry,'verifyPassword', array('placeholder' => 'Retype Password')); ?>
					</div>
				</section>

				<section class="buttons">
					<?php echo YsaHtml::submitLoadingButton('Save', array('class' => 'blue')); ?>
				</section>
				<?php $this->endWidget(); ?>
			</div>
			<div class="image">
				
			</div>
		<?php endif; ?>
	</div>
</div>

