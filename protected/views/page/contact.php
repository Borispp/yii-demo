<div class="general-page" id="contact">
	<div class="content">
		<div class="cf">
			<div class="info">
				<h2><?php echo Yii::t('general', 'Let\'s Talk!'); ?></h2>
				<?php echo $page->content; ?>
				<dl class="cf">
					<dt><?php echo Yii::t('general', 'For Support Questions'); ?></dt>
					<dd><?php echo YsaHtml::mailto(Yii::app()->settings->get('support_email'), Yii::app()->settings->get('support_email')); ?></dd>
					
					<dt><?php echo Yii::t('general', 'Matt'); ?></dt>
					<dd><?php echo YsaHtml::mailto(Yii::app()->settings->get('matt_email'), Yii::app()->settings->get('matt_email')); ?></dd>
					
					<dt><?php echo Yii::t('general', 'Gavin'); ?></dt>
					<dd><?php echo YsaHtml::mailto(Yii::app()->settings->get('gavin_email'), Yii::app()->settings->get('gavin_email')); ?></dd>
				</dl>
				
				<div class="social">
					<a href="<?php echo Yii::app()->settings->get('facebook'); ?>" class="fb" rel="external">Become a fan of YSA<br/>on <span>Facebook</span></a>
					<a href="<?php echo Yii::app()->settings->get('twitter'); ?>" class="twi" rel="external">Follow us<br/>on <span>Twitter</span></a>
				</div>
			</div>
			<div class="large-form form">
				<div id="contact-error-summary"><?php echo YsaHtml::errorSummary($entry, false); ?></div>
				<?php $form=$this->beginWidget('YsaForm', array(
						'id'=>'contact-form',
				)); ?>
				<section>
					<?php echo $form->textField($entry,'name', array('placeholder' => 'Your Name', 'required' => 'required')); ?>
				</section>

				<section>
					<?php echo $form->emailField($entry,'email', array('placeholder' => 'Email', 'required' => 'required')); ?>
				</section>

				<section>
					<?php echo $form->textField($entry,'studio_name', array('placeholder' => 'Studio Name')); ?>
				</section>

				<section>
					<?php echo $form->textField($entry,'studio_website', array('placeholder' => 'Studio Website')); ?>
				</section>

				<section>
					<?php echo $form->textField($entry,'phone_number', array('placeholder' => 'Phone Number')); ?>
				</section>

				<section>
					<?php echo $form->textArea($entry,'message', array('cols' => 40, 'rows' => 5, 'placeholder' => 'Comment / Question / Feedback', 'required' => 'required')); ?>
				</section>

				<?/*
				<?php if(extension_loaded('gd')): ?>
				<section>
					<?php echo $form->labelEx($entry, 'captcha'); ?>
					<div>
						<?php $this->widget('CCaptcha'); ?>
						<?php echo $form->textField($entry, 'captcha'); ?>
					</div>
					<div class="hint">Please enter the letters as they are shown in the image above.
					<br/>Letters are not case-sensitive.</div>
				</section>
				<?php endif; ?>
				*/?>


				<section class="buttons">
					<div class="subscribe"><?php echo $form->checkBox($entry,'subscribe'); ?> <?php echo $form->labelEx($entry,'subscribe'); ?></div>
					
					<?php echo YsaHtml::submitButton('Let Us Know', array('class' => 'blue')); ?>
				</section>

				<?php $this->endWidget(); ?>
			</div>
		</div>
		
		

	</div>
</div>