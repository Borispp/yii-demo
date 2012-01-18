<?php $this->beginContent('//layouts/main'); ?>

<?php if ($this->memberPageTitle) : ?>
	<?php echo YsaHtml::pageHeaderTitle($this->memberPageTitle); ?>
<?php endif; ?>

<div class="w">
	<?php $this->widget('YsaMemberBreadcrumbs', array(
		'links' => $this->breadcrumbs,
	)); ?>

	<div id="notifications">
		<?php $this->widget('YsaNotificationBar'); ?>
	</div>
	
</div>

<?php echo $content; ?>

<?php $this->endContent(); ?>