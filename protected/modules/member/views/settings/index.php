<section class="w">
	<section class="box">
		<div class="box-title">
			<h3>Connect Accounts</h3>
		</div>
		<div class="box-content">
			<div class="shadow-box menu-box">
				<ul>
					<li><?php echo YsaHtml::link('SmugMug Settings', array('settings/smugmug/')); ?></li>
					<li><?php echo YsaHtml::link('ZenFolio Settings', array('settings/zenfolio/')); ?></li>
					<li><?php echo YsaHtml::link('ShootQ Settings', array('settings/shootq/')); ?></li>
					<li><?php echo YsaHtml::link('Facebook Account', array('settings/facebook/')); ?></li>
					<li><?php echo YsaHtml::link('PASS Account', array('settings/pass/')); ?></li>
				</ul>
			</div>
		</div>
	</section>

	<section class="box">
		<div class="box-title">
			<h3>Change My Details</h3>
			<div class="box-title-button">
				<?php echo YsaHtml::link('<span class="icon i_inbox"></span>Announcements', array('announcement/'), array('class' => 'secondary iconed')); ?>
			</div>
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
				<section class="cf">
					<?php echo YsaHtml::label('Email important notifications','notify_by_email'); ?>
					<div>
						<?php echo YsaHtml::checkbox('notify_by_email', $notify_by_email, array('id'=>'notify_by_email')); ?>
					</div>
				</section>

				<div class="button">
					<?php echo YsaHtml::submitButton('Submit', array('class' => 'blue')); ?>
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
					<?php echo YsaHtml::submitButton('Submit', array('class' => 'blue')); ?>
				</div>

				<?php $this->endWidget();?>
			</div>
		</div>
	</section>
	
	<?php if (!$entry->isActivated()) : ?>
	<section class="box">
		<div class="box-title">
			<h3>Resend activation link</h3>
		</div>
		<div class="box-content">
			<div class="form standart-form shadow-box">
				<?php $form = $this->beginWidget('YsaForm', array(
					'id' => 'change-password-form',
					'action' => Chtml::normalizeUrl(array('settings/resendActlink'))
				)); ?>
				<div class="button">
					<?php echo YsaHtml::submitButton('Send now', array('class' => 'blue')); ?>
				</div>

				<?php $this->endWidget();?>
			</div>
		</div>
	</section>
	<?php endif ?>
	
</section>


