<div class="w" id="help-view">
	<section class="box">
		<div class="box-content">
			<div class="shadow-box cf">
				<h3><?php echo $tutorial->title; ?></h3>
				<div class="story">
					<?php echo $tutorial->content; ?>
				</div>
				<div class="paginator cf">
					<span class="prev">
						<?php if ($tutorial->previous()) : ?>
							<?php echo YsaHtml::link(YsaHelpers::truncate($tutorial->previous()->title), array('help/' . $tutorial->previous()->slug), array('title' => $tutorial->previous()->title)); ?>
						<?php endif; ?>
					</span>
					<span class="next">
						<?php if ($tutorial->next()) : ?>
							<?php echo YsaHtml::link(YsaHelpers::truncate($tutorial->next()->title), array('help/' . $tutorial->next()->slug), array('title' => $tutorial->next()->title)); ?>
						<?php endif; ?>
					</span>
					<span class="index"><?php echo YsaHtml::link('Back to Index', array('help/')); ?></span>
				</div>
			</div>
		</div>
	</section>
</div> 