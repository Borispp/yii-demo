<div class="w" id="help-view">
	<section class="box">
		<div class="box-content">
			<div class="shadow-box cf">
				<h3><?php echo $tutorial->title; ?></h3>
				
				<?php if ($tutorial->video) : ?>
					<div class="video">
						<?php echo $tutorial->video(Yii::app()->params['tutorial']['video']['width'], Yii::app()->params['tutorial']['video']['height']); ?>
					</div>
				<?php endif; ?>
				
				
				<div class="story">
					<?php echo $tutorial->content; ?>
				</div>
				
				<?php if (count($tutorial->files)) : ?>
					<div class="files">
						<h4><?php echo Yii::t('general', 'Files'); ?></h4>
						<ul>
							<?php foreach ($tutorial->files as $file) : ?>
								<li>
									<?php echo YsaHtml::link($file->name, $file->url()); ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
				
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