<div class="w">
	<h3>SmugMug Settings</h3>

	<?php $form = $this->beginWidget('YsaForm', array(
		'id'=>'smugmug-form',
		'enableAjaxValidation'=>false,
	)); ?>

	<section>
		<?php echo $form->labelEx($smug,'smug_api'); ?>
		<div>
			<?php echo $form->textField($smug,'smug_api', array('size'=>50,'maxlength'=>50)); ?>
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
		<?php echo CHtml::submitButton('Save & Authorize'); ?>
	</div>

	<?php $this->endWidget();?>

	
	
	
	<?php if ($entry->smugmugAuthorized()) : ?>
	
		<?php $smugInfo = $entry->smugmug()->auth_checkAccessToken();?>
	
		<p>Account Owner: <?php echo $smugInfo['User']['DisplayName']; ?></p>
		<p>URL: <?php echo YsaHtml::link($smugInfo['User']['URL'], $smugInfo['User']['URL'], array('target' => '_blank')); ?></p>
		<p>Account Status: <?php echo $smugInfo['User']['AccountStatus']; ?></p>
		<p>Account Type: <?php echo $smugInfo['User']['AccountType']; ?></p>
		<p>&nbsp;</p>
		<p><?php echo YsaHtml::link('Unlink SmugMug', array('settings/smugmugUnlink/')); ?></p>
	
	<?php else: ?>
		
		<?php if ($entry->option(UserOption::SMUGMUG_REQUEST)) : ?>
			<p>Please click <?php echo YsaHtml::link('this link', $entry->smugmug()->authorize(), array('target' => '_blank')); ?> to authorize to SmugMug.<br/>
			After authorization please click <?php echo YsaHtml::link('this link', array('settings/smugmug/authorize/')); ?> to complete authentication.</p>
		<?php endif; ?>
		
		
	<?php endif; ?>
	
</div>

