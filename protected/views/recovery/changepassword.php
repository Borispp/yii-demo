<div class="general-page" id="recovery-change-password">
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
					<?php echo YsaHtml::submitButton('Restore', array('class' => 'blue')); ?>
				</section>
				<?php $this->endWidget(); ?>
			</div>
			<div class="info">
				<?php echo $page->content; ?>
			</div>
		<?php endif; ?>
	</div>
</div>

