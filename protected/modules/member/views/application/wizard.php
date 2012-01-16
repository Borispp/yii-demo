<div id="app-wizard" class="w body">	
	<section id="wizard-breadcrumbs">
		<ul>
			<?php foreach ($app->wizardSteps() as $step => $stepOptions) : ?>
				<li class="<?php echo $step?><?php $step == 'logo' ? ' active' : ''?>">
					<a href="#"><span><i><?php echo $stepOptions['position']; ?></i><strong><?php echo $stepOptions['title']; ?><em><?php echo $stepOptions['title_annotation']; ?></em></strong></span></a>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>
	<section id="wizard-accordion">
		<?php foreach ($app->wizardSteps() as $step => $stepOptions) : ?>
			<section id="wizard-content-<?php echo $step?>" data-step="<?php echo $step?>" class="step box">
				<div class="box-title">
					<h3><?php echo $stepOptions['header']; ?></h3>
				</div>
				<div class="box-content">
					<?php $this->renderPartial('/wizard/' . $step, array(
						'model' => $models[$step],
					)); ?>
				</div>
			</section>
		<?php endforeach; ?>
	</section>
</div>