<?php echo YsaHtml::pageHeaderTitle('Restore'); ?>

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
		<section>
			<?php echo $form->labelEx($entry,'email'); ?>
			<div>
				<?php echo $form->textField($entry,'email'); ?>
				<?php echo $form->error($entry,'email'); ?>
			</div>
		</section>

		<section class="button">
			<?php echo CHtml::submitButton('Restore'); ?>
		</section>

		<?php $this->endWidget(); ?>
	</div>
<?php endif; ?>