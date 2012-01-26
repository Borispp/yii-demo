<?php $this->beginContent('//layouts/main'); ?>

<?php $this->widget('YsaNotificationBar'); ?>
<?php if ($this->memberPageTitle) : ?>
	<?php echo YsaHtml::pageHeaderTitle($this->memberPageTitle); ?>
<?php endif; ?>

<div class="w">
	<?php $this->widget('YsaMemberBreadcrumbs', array(
		'links' => $this->breadcrumbs,
	)); ?>
</div>

<?php echo $content; ?>

<?php $this->endContent(); ?>