<div class="w">
	<section class="box">
		<div class="box-title">
			<h3>Facebook Account Settings</h3>
		</div>
		<div class="box-content">
			<div class="shadow-box">
				<?php if ( $member->hasFacebook()) : ?>
					<p><strong>Member profile is linked to this Facebook account</strong></p>
					<div class="info-box">
						<dl class="cf">
							<dt>Facebook ID</dt>
							<dd><?php echo $fb->fb_id; ?></dd>
							
							<dt>Email</dt>
							<dd><?php echo $fb->email; ?></dd>
						</dl>
					</div>
					<?php echo YsaHtml::link('Unlink Facebook', array('settings/facebook/unlink/'), array('class' => 'btn small')); ?>

				<?php else: ?>

					<?php $this->widget('ext.eauth.EAuthWidget', array('action' => 'settings/facebook/connect')) ?>

				<?php endif ?>
			</div>
		</div>
	</section>
</div>