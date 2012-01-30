<?php echo YsaHtml::pageHeaderTitle('Restore'); ?>
<div class="w">
	<?php if(Yii::app()->user->hasFlash('recoveryMessage')): ?>
		<div class="success">
			<?php echo Yii::app()->user->getFlash('recoveryMessage'); ?>
		</div>
	<?php else: ?>
		<div class="form">
			<?php $form=$this->beginWidget('YsaForm', array(
					'id'=>'recovery-change-password-form',
					'enableAjaxValidation'=>false,
			)); ?>
			<section class="cf">
				<?php echo $form->labelEx($entry,'email'); ?>
				<div>
					<?php echo $form->textField($entry,'email'); ?>
					<?php echo $form->error($entry,'email'); ?>
				</div>
			</section>
			<div class="button">
				<?php echo CHtml::submitButton('Restore'); ?>
			</div>

			<?php $this->endWidget(); ?>
		</div>
	<?php endif; ?>
</div>
