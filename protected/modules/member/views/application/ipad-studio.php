<div class="ipad-wrapper" id="ipad-studio">
	<?php if ($application->option('studio_bg') != 'color'):?>
	<img class="main-image" src="<?php $image = $application->option('studio_bg_image'); echo $image['url']?>" alt="">
	<?php else:?>
	<div class="main-color" style="background-color: <?php echo $application->option('studio_bg_color')?>">
		<img class="studio-logo" src="<?php $image = $application->option('logo'); echo $image['url']?>" alt=""/>
	</div>
	<?php endif?>
	<div> </div>
	<div class="footer-grid">
		<h4 class="first-font studio-title">Studio</h4>
		<h4 class="first-font links-title">Links</h4>
		<h4 class="first-font feed-title">Feed</h4>
		<ul class="links">
			<li class="first-font">
				<div class="title second-font">Portfolio Site</div>
				www.photogrpaher.com
			</li>
			<li class="first-font">
				<div class="title second-font">Blog</div>
				www.photogrpaher.com/blog
			</li>
			<li class="first-font">
				<div class="title second-font">Workshops</div>
				www.photogrpaher.com/workshops
			</li>
		</ul>
		<div class="twitter-feed">
			<h4 class="second-font second-font">Twitter Feed</h4>
			<ul>
				<li>
					<div class="time">30 min. ago</div>
					<div class="message first-font">
						Consectetur adipisicing elit, sed do eius mod tempor incididunt ut labore et dolo re magna aliqua.
						<a href="" class="follow-link">Follow me</a>
					</div>
				</li>
				<li>
					<div class="time">November 20-2011</div>
					<div class="message first-font">
						Consectetur adipisicing elit, sed d
					</div>
				</li>
			</ul>
		</div>
		<img src="<?php echo Yii::app()->baseUrl?>/resources/images/ipad/<?php echo $application->option('style')?>/studio-footer-image.png" alt=""/>
	</div>
</div>