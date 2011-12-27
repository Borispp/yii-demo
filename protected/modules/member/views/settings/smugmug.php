<div class="w">
	<h3>SmugMug Settings</h3>

	<?php if ($entry->smugmugAuthorized()) : ?>
	
		<?php $smugInfo = $entry->smugmug()->auth_checkAccessToken(); ?>
	
		<p>Api Key: <?php echo $this->member()->option('smug_api'); ?></p>
		<p>Secret Key: <?php echo $this->member()->option('smug_secret'); ?></p>
	
		<p>Account Owner: <?php echo $smugInfo['User']['Name']; ?></p>
		<p>URL: <?php echo YsaHtml::link($smugInfo['User']['URL'], $smugInfo['User']['URL'], array('target' => '_blank')); ?></p>
		<p>Account Status: <?php echo $smugInfo['User']['AccountStatus']; ?></p>
		<p>Account Type: <?php echo $smugInfo['User']['AccountType']; ?></p>
		<p>&nbsp;</p>
		<p><?php echo YsaHtml::link('Unlink SmugMug', array('settings/smugmugUnlink/')); ?></p>
	
	<?php else: ?>
		
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
		
		<?php if (isset(Yii::app()->session['smugmugRequestToken'])) : ?>
			<p>Please click <?php echo YsaHtml::link('this link', $entry->smugmug()->authorize(), array('target' => '_blank', 'id' => 'settings-smugmug-authorize')); ?> to authorize to SmugMug.<br/>
			After authorization please click <?php echo YsaHtml::link('this link', array('settings/smugmug/authorize/')); ?> to complete authentication.</p>
		<?php endif; ?>
			
	<?php endif; ?>
	
</div>



<?php if (isset(Yii::app()->session['smugmugRequestToken'])) : ?>
<script type="text/javascript">
	$(function(){
		$('#settings-smugmug-authorize').click(function(e){
			e.preventDefault();
			var smugmug_window = window.open($(this).attr('href'), 'smugmugWindow', 'width=800,height=500');
			var watchClose = setInterval(function() {
				try {
					if (smugmug_window.closed) {
						clearInterval(watchClose);
						smugmug_window.close();
						window.location = '<?php echo Yii::app()->createAbsoluteUrl('member/settings/smugmug/authorize/') ?>';
					}
				} catch (e) {}
			}, 200);
		})
	});
</script>
<?php endif; ?>

