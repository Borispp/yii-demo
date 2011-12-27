<section class="w">
	<h3><?php echo $entry->name(); ?></h3>
	
	<div><?php echo YsaHtml::link('Inbox', array('inbox/')); ?></div>
	<div><?php echo YsaHtml::link('My Subscriptions', array('subscription/list/')); ?></div>
	
	<div><?php echo YsaHtml::link('SmugMug Settings', array('settings/smugmug/')); ?></div>
	<div><?php echo YsaHtml::link('ShootQ Settings', array('settings/shootq/')); ?></div>
	<div><?php echo YsaHtml::link('500px Settings', array('settings/500px/')); ?></div>
	<div><?php echo YsaHtml::link('Zenfolio Settings', array('settings/zenfolio/')); ?></div>
	
	
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

	
	<h3>Change Password</h3>
	
	<?php $form = $this->beginWidget('YsaForm', array(
		'id'=>'change-password-form',
		'enableAjaxValidation'=>false,
	)); ?>

	<section>
		<?php echo $form->labelEx($password,'currentPassword'); ?>
		<div>
			<?php echo $form->passwordField($password,'currentPassword', array('size'=>60,'maxlength'=>50)); ?>
			<?php echo $form->error($password,'currentPassword'); ?>
		</div>
	</section>

	<section>
		<?php echo $form->labelEx($password,'newPassword'); ?>
		<div>
			<?php echo $form->passwordField($password,'newPassword',array('size'=>50,'maxlength'=>50)); ?>
			<?php echo $form->error($password,'newPassword'); ?>
		</div>
	</section>

	<section>
		<?php echo $form->labelEx($password,'repeatPassword'); ?>
		<div>
			<?php echo $form->passwordField($password,'repeatPassword',array('size'=>50,'maxlength'=>50)); ?>
			<?php echo $form->error($password,'repeatPassword'); ?>
		</div>
	</section>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

	<?php $this->endWidget();?>





</section>


