<section class="body w">
	<h3><?php echo $entry->name(); ?></h3>
	<div><a href="<?php echo Yii::app()->createUrl('/member/subscription/list/')?>">My subscriptions</a></div>
	<div><a href="<?php echo Yii::app()->createUrl('/member/inbox')?>">Inbox</a></div>
	<p class="descr">Lorem ipsum dccolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore.</p>

	<div class="form">
		<?php $form=$this->beginWidget('YsaForm', array(
			'id'=>'settings-form',
			'enableAjaxValidation'=>false,
		)); ?>

		<section>
			<?php echo $form->labelEx($entry,'email'); ?>
			<div>
				<?php echo $form->textField($entry,'email', array('size'=>60,'maxlength'=>100)); ?>
				<?php echo $form->error($entry,'email'); ?>
			</div>
		</section>

		<section>
			<?php echo $form->labelEx($entry,'first_name'); ?>
			<div>
				<?php echo $form->textField($entry,'first_name',array('size'=>50,'maxlength'=>50)); ?>
				<?php echo $form->error($entry,'first_name'); ?>
			</div>
		</section>

		<section>
			<?php echo $form->labelEx($entry,'last_name'); ?>
			<div>
				<?php echo $form->textField($entry,'last_name',array('size'=>50,'maxlength'=>50)); ?>
				<?php echo $form->error($entry,'last_name'); ?>
			</div>
		</section>

		<div class="row buttons">
			<?php echo CHtml::submitButton('Submit'); ?>
		</div>

		<?php $this->endWidget();?>
	</div>

	<h3>ShootQ Settings</h3>
	
	<?php $form = $this->beginWidget('YsaForm', array(
		'id'=>'shootq-form',
		'enableAjaxValidation'=>false,
	)); ?>

	<section>
		<?php echo $form->labelEx($shootq,'shootq_enabled'); ?>
		<?php echo $form->checkBox($shootq,'shootq_enabled',array('checked' => $shootq->shootq_enabled)); ?>
		<div>
			<?php echo $form->error($shootq,'shootq_enabled'); ?>
		</div>
	</section>
	
	<section>
		<?php echo $form->labelEx($shootq,'shootq_abbr'); ?>
		<div>
			<?php echo $form->textField($shootq,'shootq_abbr', array('size'=>60,'maxlength'=>50)); ?>
			<?php echo $form->error($shootq,'shootq_abbr'); ?>
		</div>
	</section>

	<section>
		<?php echo $form->labelEx($shootq,'shootq_key'); ?>
		<div>
			<?php echo $form->textField($shootq,'shootq_key',array('size'=>50,'maxlength'=>50)); ?>
			<?php echo $form->error($shootq,'shootq_key'); ?>
		</div>
	</section>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

	<?php $this->endWidget();?>
	
	
	
	
	
	<h3>SmugMug Settings</h3>
	
	<?php $form = $this->beginWidget('YsaForm', array(
		'id'=>'smugmug-form',
		'enableAjaxValidation'=>false,
	)); ?>

	<section>
		<?php echo $form->labelEx($smug,'smug_api'); ?>
		<div>
			<?php echo $form->textField($smug,'smug_api', array('size'=>60,'maxlength'=>50)); ?>
			<?php echo $form->error($smug,'smug_api'); ?>
		</div>
	</section>

	<section>
		<?php echo $form->labelEx($smug,'smug_secret'); ?>
		<div>
			<?php echo $form->textField($smug,'smug_secret',array('size'=>50,'maxlength'=>50)); ?>
			<?php echo $form->error($smug,'smug_secret'); ?>
		</div>
	</section>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

	<?php $this->endWidget();?>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	<h3>Change Password</h3>
	
	<?php $form = $this->beginWidget('YsaForm', array(
		'id'=>'change-password-form',
		'enableAjaxValidation'=>false,
	)); ?>

	<section>
		<?php echo $form->labelEx($password,'currentPassword'); ?>
		<div>
			<?php echo $form->textField($password,'currentPassword', array('size'=>60,'maxlength'=>50)); ?>
			<?php echo $form->error($password,'currentPassword'); ?>
		</div>
	</section>

	<section>
		<?php echo $form->labelEx($password,'newPassword'); ?>
		<div>
			<?php echo $form->textField($password,'newPassword',array('size'=>50,'maxlength'=>50)); ?>
			<?php echo $form->error($password,'newPassword'); ?>
		</div>
	</section>

	<section>
		<?php echo $form->labelEx($password,'repeatPassword'); ?>
		<div>
			<?php echo $form->textField($password,'repeatPassword',array('size'=>50,'maxlength'=>50)); ?>
			<?php echo $form->error($password,'repeatPassword'); ?>
		</div>
	</section>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

	<?php $this->endWidget();?>





</section>


