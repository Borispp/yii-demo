<section class="w">
	<section class="box">
		<div class="box-title">
			<h3><?php echo $entry->name(); ?></h3>
		</div>
		<div class="box-content">
			<div><?php echo YsaHtml::link('Inbox', array('inbox/')); ?></div>
			<div><?php echo YsaHtml::link('My Subscriptions', array('subscription/list/')); ?></div>
			<div><?php echo YsaHtml::link('SmugMug Settings', array('settings/smugmug/')); ?></div>
			<div><?php echo YsaHtml::link('ShootQ Settings', array('settings/shootq/')); ?></div>
			<div><?php echo YsaHtml::link('Facebok Account', array('settings/facebook/')); ?></div>
		</div>
	</section>


	<section class="box">
		<div class="box-title">
			<h3>Change Details</h3>
		</div>
		<div class="box-content">
			<div class="form standart-form shadow-box">
				<?php $form=$this->beginWidget('YsaForm', array(
					'id'=>'settings-form',
				)); ?>

				<section class="cf">
					<?php echo $form->labelEx($entry,'email'); ?>
					<div>
						<?php echo $form->textField($entry,'email', array('size'=>60,'maxlength'=>100)); ?>
						<?php echo $form->error($entry,'email'); ?>
					</div>
				</section>

				<section class="cf">
					<?php echo $form->labelEx($entry,'first_name'); ?>
					<div>
						<?php echo $form->textField($entry,'first_name',array('size'=>50,'maxlength'=>50)); ?>
						<?php echo $form->error($entry,'first_name'); ?>
					</div>
				</section>

				<section class="cf">
					<?php echo $form->labelEx($entry,'last_name'); ?>
					<div>
						<?php echo $form->textField($entry,'last_name',array('size'=>50,'maxlength'=>50)); ?>
						<?php echo $form->error($entry,'last_name'); ?>
					</div>
				</section>

				<div class="button">
					<?php echo YsaHtml::submitButton('Submit'); ?>
				</div>

				<?php $this->endWidget();?>
			</div>
		</div>
	</section>
	
	<section class="box">
		<div class="box-title">
			<h3>Change Password</h3>
		</div>
		<div class="box-content">
			<div class="form standart-form shadow-box">
				<?php $form = $this->beginWidget('YsaForm', array(
					'id'=>'change-password-form',
				)); ?>
				<section class="cf">
					<?php echo $form->labelEx($password,'currentPassword'); ?>
					<div>
						<?php echo $form->passwordField($password,'currentPassword', array('size'=>60,'maxlength'=>50)); ?>
						<?php echo $form->error($password,'currentPassword'); ?>
					</div>
				</section>

				<section class="cf">
					<?php echo $form->labelEx($password,'newPassword'); ?>
					<div>
						<?php echo $form->passwordField($password,'newPassword',array('size'=>50,'maxlength'=>50)); ?>
						<?php echo $form->error($password,'newPassword'); ?>
					</div>
				</section>

				<section class="cf">
					<?php echo $form->labelEx($password,'repeatPassword'); ?>
					<div>
						<?php echo $form->passwordField($password,'repeatPassword',array('size'=>50,'maxlength'=>50)); ?>
						<?php echo $form->error($password,'repeatPassword'); ?>
					</div>
				</section>

				<div class="button">
					<?php echo YsaHtml::submitButton('Submit'); ?>
				</div>

				<?php $this->endWidget();?>
			</div>
		</div>
	</section>
</section>


