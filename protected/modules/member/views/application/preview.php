<div class="w" id="application-preview">
	<div class="box-preview">		
		<div class="buttons">
			<?php echo YsaHtml::link('Change Settings', array('wizard'), array('class' => 'btn')); ?>
			<?php if (!$app->submitted()) : ?>
				<?php echo YsaHtml::link('Submit for Review', array('submit'), array('class' => 'btn blue')); ?>
			<?php endif; ?>
		</div>
		<div id="ipad">
			<span class="bg"></span>
			<div class="case">
				
			</div>
		</div>
		<div class="buttons">
			<?php echo YsaHtml::link('Change Settings', array('wizard'), array('class' => 'btn')); ?>
			<?php if (!$app->submitted()) : ?>
				<?php echo YsaHtml::link('Submit for Review', array('submit'), array('class' => 'btn blue')); ?>
			<?php endif; ?>
		</div>
	</div>
</div>