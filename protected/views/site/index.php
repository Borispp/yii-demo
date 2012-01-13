<?php echo YsaHtml::pageHeaderTitle($page->title); ?>
<div class="body w">
    <?php echo $page->content; ?>
	
	<div id="newsletter-subscribe">
		<h3>Subscribe to our newsletter</h3>
		
		<?php if (Yii::app()->user->hasFlash('newsletterSubscribed')) : ?>
			<p><?php echo Yii::app()->user->getFlash('newsletterSubscribed'); ?></p>
			<p>Click <?php echo YsaHtml::link('here', array('/')); ?> to subscribe with another email.</p>
		<?php else: ?>
			<?php $this->renderPartial('/newsletter/_form', array(
				'newsletterForm' => $newsletterForm,
			)); ?>
		<?php endif; ?>
	</div>
</div>