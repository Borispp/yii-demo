<?php $this->beginContent('//layouts/main'); ?>

<?php if ($this->memberPageTitle) : ?>
	<?php echo YsaHtml::pageHeaderTitle($this->memberPageTitle); ?>
<?php endif; ?>

<div class="w">
	<?php $this->widget('YsaMemberBreadcrumbs', array(
		'links' => $this->breadcrumbs,
	)); ?>

	<?php $this->widget('YsaNotificationBar'); ?>
	
</div>

<?php echo $content; ?>

<?php $this->endContent(); ?>