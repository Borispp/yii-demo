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
			<div id="wizard-content-<?php echo $step?>" data-step="<?php echo $step?>" class="step">
				<h3><?php echo $stepOptions['header']; ?></h3>
				<div class="content">
					<?php $this->renderPartial('/wizard/' . $step, array(
						'model' => $models[$step],
					)); ?>
				</div>
			</div>
		<?php endforeach; ?>
		
		
		<?/*

		<div id="wizard-content-logo" data-step="1" class="step">
			<h3>Upload your logos</h3>
			<div class="content">
				<?php $this->renderPartial('/wizard/logo', array(
					'model' => $models['logo'],
				)); ?>
			</div>
		</div>
		
		<div id="wizard-content-colors" data-step="2" class="step">
			<h3>Colors</h3>
			<div class="content">
				<?php $this->renderPartial('/wizard/colors', array(
					'model' => $models['colors'],
				)); ?>
			</div>
		</div>
		
		<div id="wizard-content-fonts" data-step="3" class="step">
			<h3>Fonts</h3>
			<div class="content">
				<?php $this->renderPartial('/wizard/fonts', array(
					'model' => $models['fonts'],
				)); ?>
			</div>
		</div>
		
		<div id="wizard-content-copyrights" data-step="4" class="step">
			<h3>Copyrights</h3>
			<div class="content">
				<?php $this->renderPartial('/wizard/copyrights', array(
					'model' => $models['copyrights'],
				)); ?>
			</div>
		</div>
		
		<div id="wizard-content-submit" data-step="5" class="step">
			<h3>Submit your application</h3>
			<div class="content">
				<?php $this->renderPartial('/wizard/submit', array(
					'model' => $models['submit'],
				)); ?>
			</div>
		</div>
		*/?>
	</section>
</div>