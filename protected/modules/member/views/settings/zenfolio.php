
<div class="w">
	<section class="box">
		<div class="box-title">
			<h3>ZenFolio Integration</h3>
		</div>
		<div class="box-content">
			<div class="shadow-box">
				<?php if ($this->member()->zenfolioAuthorized()) : ?>
				
					<?php $zenfolioProfile = $this->member()->zenfolio()->LoadPrivateProfile(); ?>
					<div class="info-box">
						<dl class="cf">
							
							<dt>Name</dt>
							<dd><?php echo $zenfolioProfile['DisplayName']?></dd>
							
							<dt>Email</dt>
							<dd><?php echo $zenfolioProfile['PrimaryEmail']?></dd>
							
							<dt>Referral Code</dt>
							<dd><?php echo $zenfolioProfile['ReferralCode']; ?></dd>
							
							<dt>&nbsp</dt>
							<dd>&nbsp;</dd>
							
							<dt>&nbsp</dt>
							<dd><?php echo YsaHtml::link('Unlink ZenFolio', array('settings/zenfolioUnlink/'), array('class' => 'btn small')); ?></dd>
						</dl>
					</div>

				<?php else: ?>

				<div class="form standart-form">
	
					<?php $form = $this->beginWidget('YsaForm', array(
						'id'=>'zenfolio-form',
						'enableAjaxValidation'=>false,
					)); ?>

					<section class="cf">
						<?php echo $form->labelEx($zenlogin,'username'); ?>
						<div>
							<?php echo $form->textField($zenlogin,'username', array('size'=>50,'maxlength'=>50)); ?>
							<?php echo $form->error($zenlogin,'username'); ?>
						</div>
					</section>

					<section class="cf">
						<?php echo $form->labelEx($zenlogin,'password'); ?>
						<div>
							<?php echo $form->textField($zenlogin,'password',array('size'=>50,'maxlength'=>50)); ?>
							<?php echo $form->error($zenlogin,'password'); ?>
						</div>
					</section>

					<div class="row buttons">
						<?php echo CHtml::submitButton('Authorize'); ?>
					</div>

					<?php $this->endWidget();?>

				</div>

				<?php endif; ?>
			</div>
		</div>
	</section>
</div>

