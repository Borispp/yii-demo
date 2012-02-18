<div class="general-page" id="recover">
	<div class="content cf">
		<?php if(Yii::app()->user->hasFlash('recoveryMessage')): ?>
			<div id="recover-success">
				<span class="icon <?php echo Yii::app()->user->getFlash('recoveryStatus') == 'changed' ? 'lock' : 'mail'?>"></span>
				<div class="message">
					<?php echo  Yii::app()->user->getFlash('recoveryMessage'); ?>
					<?php if (Yii::app()->user->getFlash('recoveryStatus') == 'changed') : ?>
						<div class="buttons c"><?php echo YsaHtml::link('Login Now', array('login/'), array('class' => 'btn blue')); ?></div>
					<?php else:?>
						<div class="buttons c"><?php echo YsaHtml::link('Go back', '/', array('class' => 'btn blue')); ?></div>
					<?php endif;?>
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
			<div class="image">
				
			</div>
		<?php endif; ?>
	</div>

</div>