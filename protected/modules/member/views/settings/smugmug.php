<div class="w">
	
	<section class="box">
		<div class="box-title">
			<h3>SmugMug Settings</h3>
		</div>
		<div class="box-content">
			<div class="shadow-box">
				<?php if ($entry->smugmugAuthorized()) : ?>
				
					<?php $smugInfo = $entry->smugmug()->auth_checkAccessToken(); ?>
					<div class="info-box">
						<dl class="cf">
							<dt>Api Key</dt>
							<dd><?php echo $this->member()->option('smug_api'); ?></dd>

							<dt>Secret Key</dt>
							<dd><?php echo $this->member()->option('smug_secret'); ?></dd>

							<dt>Account Owner</dt>
							<dd><?php echo $smugInfo['User']['Name']; ?></dd>

							<dt>URL</dt>
							<dd><?php echo YsaHtml::link($smugInfo['User']['URL'], $smugInfo['User']['URL'], array('target' => '_blank')); ?></dd>

							<dt>Account Status</dt>
							<dd><?php echo $smugInfo['User']['AccountStatus']; ?></dd>
							
							<dt>Account Type</dt>
							<dd><?php echo $smugInfo['User']['AccountType']; ?></dd>
							
							<dt>&nbsp</dt>
							<dd>&nbsp;</dd>
							
							<dt>&nbsp</dt>
							<dd><?php echo YsaHtml::link('Unlink SmugMug', array('settings/smugmugUnlink/'), array('class' => 'btn small')); ?></dd>
						</dl>
					</div>

				<?php else: ?>

				<div class="form standart-form">
					<?php $form = $this->beginWidget('YsaForm', array(
						'id'=>'smugmug-form',
						'enableAjaxValidation'=>false,
					)); ?>

					<section class="cf">
						<?php echo $form->labelEx($smug,'smug_api'); ?>
						<div>
							<?php echo $form->textField($smug,'smug_api', array('size'=>50,'maxlength'=>50)); ?>
							<?php echo $form->error($smug,'smug_api'); ?>
						</div>
					</section>

					<section class="cf">
						<?php echo $form->labelEx($smug,'smug_secret'); ?>
						<div>
							<?php echo $form->textField($smug,'smug_secret',array('size'=>50,'maxlength'=>50)); ?>
							<?php echo $form->error($smug,'smug_secret'); ?>
						</div>
					</section>

					<div class="row buttons">
						<?php echo CHtml::submitButton('Save & Authorize'); ?>
					</div>

					<?php if (isset(Yii::app()->session['smugmugRequestToken'])) : ?>
					<div class="info">
						<p>Please click <?php echo YsaHtml::link('this link', $entry->smugmug()->authorize(), array('target' => '_blank', 'id' => 'settings-smugmug-authorize')); ?> to authorize to SmugMug.<br/>
						After authorization please click <?php echo YsaHtml::link('this link', array('settings/smugmug/authorize/')); ?> to complete authentication.</p>
					</div>
					<?php endif; ?>
					
					<?php $this->endWidget();?>


				</div>

				<?php endif; ?>
			</div>
		</div>
	</section>
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

