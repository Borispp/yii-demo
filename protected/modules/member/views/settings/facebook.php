<div class="w">
	<h3>Facebook Account Settings</h3>
	
	<?php if ( $member->isFacebookConnected() ) : ?>
		
		<p>Member profile is linked to this Facebook account</p>
	
		<p>Facebook ID: <?php echo $fb->fb_id ?></p>
		<p>Email: <?php echo $fb->email ?></p>
		
		<?php echo YsaHtml::link('Unlink Facebook', array('settings/facebook/unlink/')); ?>
		
	<?php else: ?>
	
		<?php $this->widget('ext.eauth.EAuthWidget', array('action' => 'settings/facebook/connect')) ?>
	
	<?php endif ?>
	
</div>