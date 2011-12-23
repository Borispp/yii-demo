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
			<?php echo $form->labelEx($entry,'password'); ?>
			<div>
				<?php echo $form->passwordField($entry,'password'); ?>
				<?php echo $form->error($entry,'password'); ?>
			</div>
		</section>

		<section>
			<?php echo $form->labelEx($entry,'verifyPassword'); ?>
			<div>
				<?php echo $form->passwordField($entry,'verifyPassword'); ?>
				<?php echo $form->error($entry,'verifyPassword'); ?>
			</div>
		</section>

		<section class="button">
			<?php echo CHtml::submitButton('Restore'); ?>
		</section>

		<?php $this->endWidget(); ?>
	</div>
<?php endif; ?>