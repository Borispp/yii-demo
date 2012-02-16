<div id="coming-soon">
	<div class="header-wrapper">
		<div class="header">
			<div class="message">
				<?php echo $page->short; ?>
			</div>
			<h2>YourStudioApp</h2>
		</div>
	</div>
	<div class="container-wrapper">
		<div class="container cf">
			<div class="subscribe" id="signup-newsletter-subscribe">
				<h2>Sign Up Here</h2>
				<p>to be notified of when you can create your account and begin designing your application!</p>
				<?php if (Yii::app()->user->hasFlash('newsletterSubscribed')) : ?>
					<p><?php echo Yii::app()->user->getFlash('newsletterSubscribed'); ?></p>
					<p>Click <?php echo YsaHtml::link('here', array('/')); ?> to subscribe with another email.</p>
				<?php else: ?>
					<div class="large-form">
						<?php $this->renderPartial('/newsletter/_form', array(
							'newsletterForm' => $newsletterForm,
						)); ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="screens">
				<ul>
					<?php for ($i = 1; $i <= 4; $i++):?>
					<li><?php echo YsaHtml::image(Yii::app()->baseUrl . '/resources/images/comingsoon/pic' . $i . '.png'); ?></li>
					<?php endfor;?>
				</ul>
			</div>
		</div>
	</div>
</div>