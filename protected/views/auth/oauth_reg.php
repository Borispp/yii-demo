<div class="general-page" id="login-register">
	<div class="content cf">
		
		<?php if ($page) : ?>
			<div class="page">
				<?php echo $page->body; ?>
			</div>
		<?php endif; ?>

		<div class="oauth_register">
			<h2><?php echo Yii::t('general', 'Create an account'); ?></h2>
			
			<?php $this->renderPartial('/register/_form', array(
				'model' => $register,
				'facebook_button' => false,
			))?>
		</div>
	
		<div class="login">
		
			<h2><?php echo Yii::t('general', 'Already have an account?'); ?></h2>
			
			&larr; <?php echo YsaHtml::link('Return to Login page', CHtml::normalizeUrl(array('auth/login/')), array('class' => 'blue')); ?>

		</div>
		
	</div>
	
</div> 