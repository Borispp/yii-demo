<div class="ipad-wrapper" id="ipad-home">
	<?php if ($application->option('splash_bg') != 'color'):?>
	<img class="main-image" src="<?php $image = $application->option('splash_bg_image'); echo $image['url']?>" alt="">
	<?php else:?>
	<div class="main-color" style="background-color: <?php echo $application->option('splash_bg_color')?>">
		<img class="studio-logo" src="<?php $image = $application->option('logo'); echo $image['url']?>" alt=""/>
	</div>
	<?php endif?>
	<div class="bottom-tab">&nbsp;</div>
</div>