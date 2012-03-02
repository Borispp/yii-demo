<div class="general-page" id="<?php echo $page->slug?>">
	<div class="content">
		<p><?php echo YsaHtml::encode($message); ?></p>
		<?php if ($errCode == 403) : ?>
			<span class="button"><?php echo YsaHtml::link('&#8672; Back', YsaHttpRequest::getUrlReferrer() ? YsaHttpRequest::getUrlReferrer() : Yii::app()->homeUrl, array('class' => 'btn blue')); ?></span>
		<?php endif; ?>
	</div>
</div>