<div id="homepage">
	<div class="homepage-header-wrapper">
		<section id="homepage-header">
			<h2>YourStudioApp</h2>
			<a href="<?php echo Yii::app()->createUrl('/login')?>" class="signup">
				<span class="sign">Sign Up Now</span>
				<span class="arr">&gt;</span>
				<span class="click">click here</span>
			</a>
			<div id="homepage-slider">
				<ul>
					<?php foreach ($slides as $k => $slide) : ?>
						<li class="slide slide<?php echo $k?>">
							<figure>
								<?php echo YsaHtml::image($slide['image']) ?>
							</figure>
							<p><?php echo $slide['caption']; ?></p>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>
	</div>
	<div class="homepage-video-wrapper">
		<section id="homepage-video">
			<div class="content">
				<?/*
				<h2>Watch the<span>YsaStudioApp Video</span></h2>
				<p>Hey everyone! So great to have you be a part of YSA's launch! Less than 24hrs. into our announcement and we've already been getting some great feedback and we appreciate you spreading the word! We know you're intrigued about what's to come!</p>
				 */?>
				<h2>Watch the <span>YourStudioApp Video</span></h2>
				
				<p>What Exactly is Your Studio App? <br/>Watch This Quick Video to Find Out!</p>
				
				<div class="buttons">
					<?php echo YsaHtml::link('Tour', array('/tour'), array('class' => 'btn black')); ?>
					<span></span>
					<?php echo YsaHtml::link('Get Started', array('/login'), array('class' => 'btn blue')); ?>
				</div>
			</div>
			<div class="video">
				<?php echo YsaHtml::link('<span>preview</span>', array('/video'), array('class' => 'preview')); ?>
			</div>
		</section>
	</div>
	<div class="homepage-social-wrapper">
		<div id="homepage-social">
			<span class="image"></span>
			<div class="info">
				<h3>Get connected to our <span>social life</span></h3>
				<ul>
					<li>
						<a href="<?php echo Yii::app()->settings->get('facebook'); ?>" class="fb" rel="external">Become a fan of YSA on <span>Facebook</span></a>
					</li>
					<li>
						<a href="<?php echo Yii::app()->settings->get('twitter'); ?>" class="twi" rel="external">Recent <span>Twitter</span> feed</a>
						<div class="feed">
							<?php foreach (YsaTwitterReader::getTweets(Yii::app()->settings->get('twitter')) as $tweet) : ?>
								<?php echo $tweet['tweet']; ?>
							<?php endforeach; ?>
							<?php echo YsaHtml::link('Follow&nbsp;Us', Yii::app()->settings->get('twitter'), array('rel' => 'external', 'class' => 'follow')); ?>
						</div>
					</li>
				</ul>
			</div>
			<div class="subscribe" id="newsletter-subscribe">
				<h3>Subscribe to newsletter</h3>
				<?php if (Yii::app()->user->hasFlash('newsletterSubscribed')) : ?>
					<p><?php echo Yii::app()->user->getFlash('newsletterSubscribed'); ?></p>
					<p>Click <?php echo YsaHtml::link('here', array('/')); ?> to subscribe with another email.</p>
				<?php else: ?>
					<?php $this->renderPartial('/newsletter/_form', array(
						'newsletterForm' => $newsletterForm,
					)); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>