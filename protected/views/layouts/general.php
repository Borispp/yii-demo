<?php $this->beginContent('/layouts/main'); ?>
	<?php if ($this->frontPageTitle) : ?>
		<?php echo YsaHtml::pageHeaderTitle($this->frontPageTitle); ?>
	<?php endif; ?>
    <?php echo $content; ?>
<?php $this->endContent(); ?>