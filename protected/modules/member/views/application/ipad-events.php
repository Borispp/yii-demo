<div class="ipad-wrapper" id="ipad-events">
	<div class="events-big">
		<div class="logo">
			<img class="studio-logo" src="<?php $image = $application->option('logo'); echo $image['url']?>" alt=""/>
		</div>
		<h4 id="title1" class="first-font <?php echo $application->option('main_font')?>">Samantha and Richard<br/>Engagement Party</h4>
		<h4 id="title2" class="first-font <?php echo $application->option('main_font')?>">My wedding</h4>
		<h4 id="title3" class="first-font <?php echo $application->option('main_font')?>">Amanda and Albert</h4>
		<h4 id="title4" class="first-font <?php echo $application->option('main_font')?>">Madeline and Ralph</h4>
		<img src="<?php echo Yii::app()->baseUrl?>/resources/images/ipad/<?php echo $application->option('style')?>/events.png"/>
	</div>

	<?php if ($application->option('generic_bg') != 'color'):?>
	<div class="events-bg">
		<img src="<?php $image = $application->option('generic_bg_image'); echo $image['url']?>" alt="">
	</div>
	<?php else:?>
	<div class="events-bg" style="background:<?php echo $application->option('generic_bg_color')?>">&nbsp;</div>
	<?php endif;?>
</div>