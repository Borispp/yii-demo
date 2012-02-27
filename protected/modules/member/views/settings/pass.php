<div class="w">
	<section class="box">
		<div class="box-title">
			<h3>PASS account settings</h3>
		</div>
		<div class="box-content">
			<div class="shadow-box">
				<?php if ($pass_form->isLinked($this->member())) : ?>
				
					<?php // $pass_form = $entry->smugmug()->auth_checkAccessToken(); ?>
					<div class="info-box">
						<dl class="cf">
							<dt>Login (email)</dt>
							<dd><?php echo $this->member()->option(UserOption::PASS_EMAIL); ?></dd>

							<dt>Password</dt>
							<?php $pass = $this->member()->option(UserOption::PASS_PASSWORD) ?>
							<dd><?php echo preg_replace('#.{1}#', '*', $pass, strlen($pass)-3); ?></dd>
							
							<dt>&nbsp</dt>
							<dd>&nbsp;</dd>
							
							<dt>&nbsp</dt>
							<dd><?php echo YsaHtml::link('Unlink PASS', array('settings/passUnlink/'), array('class' => 'btn small')); ?></dd>
						</dl>
					</div>

				<?php else: ?>

				<div class="form standart-form">
					<?php $form = $this->beginWidget('YsaForm', array(
						'id'=>'pass-form',
						'enableAjaxValidation'=>false,
					)); ?>

					<section class="cf">
						<?php echo $form->labelEx($pass_form,'email'); ?>
						<div>
							<?php echo $form->textField($pass_form,'email', array('size'=>50,'maxlength'=>50)); ?>
							<?php echo $form->error($pass_form,'email'); ?>
						</div>
					</section>

					<section class="cf">
						<?php echo $form->labelEx($pass_form,'password'); ?>
						<div>
							<?php echo $form->passwordField($pass_form,'password',array('size'=>50,'maxlength'=>50)); ?>
							<?php echo $form->error($pass_form,'password'); ?>
						</div>
					</section>

					<div class="row buttons">
						<?php echo CHtml::submitButton('Save & Authorize'); ?>
					</div>

					<?php if (false && isset(Yii::app()->session['smugmugRequestToken'])) : ?>
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

