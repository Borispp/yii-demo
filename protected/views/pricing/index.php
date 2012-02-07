<div class="general-page" id="pricing">
	<div class="content">
		<div class="idea">
			<h2><?php echo Yii::t('general', 'The Idea'); ?></h2>
			<span class="image"></span>
		</div>
		
		<div class="cf">
			<div class="sign">
				<h1><?php echo $signupnow->title; ?></h1>
				<?php echo $signupnow->content; ?>
			</div>
			<div class="price">
				<div class="image cf">
					<div class="app">
						<h2>Application Creation</h2>
						<span class="p"><del>$1000</del><ins>$500</ins></span>
						<ul>
							<li>Development of your Application by YSA</li>
							<li>Review and Submission to the App Store</li>
							<li>1-on-1 Direct Support at any Time During the Creation Process</li>
						</ul>
					</div>
					<span class="separator"></span>
					<span class="and">and</span>
					<div class="host">
						<h2>Hosting</h2>
						<span class="p"><del>$20</del><ins>free</ins></span>
						<ul>
							<li>Maintains and Allows your Application to Remain Free to Download by Anyone in the App Store</li>
							<li>Web Back End Hosting and Instant Access to Your Application</li>
							<li>Eligible for all YSA Software and Feature Updates</li>
						</ul>
					</div>
				</div>
				<div class="get-started">
					<?php echo YsaHtml::link('Get Started', array('/login'), array('class' => 'btn')); ?>
				</div>
				<p>
					Introductory pricing is for a very limited time, <br/>be one of the first and get started on creating your application!
				</p>
			</div>
		</div>
	</div>
</div>