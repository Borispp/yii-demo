<div class="w" id="application">
	<section class="box">
		<div class="box-title">
			<h3><?php echo $page->title; ?></h3>
		</div>
		<div class="box-content">
			<?php echo $page->content; ?>
			<div class="buttons-block">
				<span class="button"><?php echo YsaHtml::link('Accept', array('submit'), array('class' => 'btn blue')); ?></span>
				<span class="button"><?php echo YsaHtml::link('Deny', array('view'), array('class' => 'btn blue')); ?></span>
			</div>
		</div>
	</section>
</div>