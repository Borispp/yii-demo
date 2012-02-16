<div class="w" id="help-index">
	<section class="box">
		<div class="box-title">
			<h3><?php echo Yii::t('title', 'tutorials'); ?></h3>
		</div>
		<div class="box-content">
			<div class="shadow-box cf">
				<div class="page">
					<?php foreach ($categories as $category) : ?>
						<?php if (count($category->tutorials)) : ?>
							<div class="category">
								<a name="<?php echo $category->id; ?>"></a>
								<h4><?php echo $category->name; ?></h4>
								<ul>
									<?php foreach ($category->tutorials as $tutorial) : ?>
										<li>
											<?php echo YsaHtml::link($tutorial->title, array('help/' . $tutorial->slug)); ?>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
</div> 