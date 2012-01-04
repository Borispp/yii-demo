<div class="w">
	<?php if ($this->member()->five00pxAuthorized()) : ?>
		<?php $fiveUser = $this->member()->five00px()->get('users'); ?>

		<p>Account Owner: <?php echo $fiveUser->user->fullname; ?></p>
		<p>URL: <?php echo YsaHtml::link($fiveUser->user->domain, 'http://' . $fiveUser->user->domain, array('target' => '_blank')); ?></p>
		<p>Photos Count: <?php echo $fiveUser->user->photos_count; ?></p>
		<p><?php echo YsaHtml::link('Unlink 500px', array('settings/500pxUnlink/')); ?></p>
	<?php else: ?>
		<p>Please click this <?php echo YsaHtml::link('link', $this->member()->five00px()->getAuthorizeURL(Yii::app()->session['500pxRequestToken']['oauth_token']), array('id' => 'settings-500px-authorize')); ?> to authorize </p>
	<?php endif; ?>
	
</div>