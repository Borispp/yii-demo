<div class="w" id="application-agreement">
	<section class="box">
		<div class="box-title">
			<h3><?php echo $page->title; ?></h3>
		</div>
		<div class="box-content">
			
			<div class="shadow-box">
				<div class="agreement">
					<?php echo $page->content; ?>

					<?php echo $page->content; ?>

					<?php echo $page->content; ?>
				</div>
				<div class="buttons-block">
					<span class="button"><?php echo YsaHtml::link('Accept', array('submit'), array('class' => 'btn blue')); ?></span>
					<span class="button"><?php echo YsaHtml::link('Deny', array('view'), array('class' => 'btn blue')); ?></span>
				</div>
			</div>
		</div>
	</section>
</div>