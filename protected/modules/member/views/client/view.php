<script type="text/javascript" src="<?php echo Yii::app()->baseUrl?>/resources/js/member/client.js"></script>
<div class="w">
	<section class="box">
		<div class="box-title">
			<h3><?php echo $entry->name; ?></h3>
			<div class="box-title-button">
				<?php echo YsaHtml::link('<span class="icon i_chat"></span>Send Push Notification', '/member/notification/new?type=client&recipient='.$entry->id, array('class' => 'secondary iconed', 'id' => 'send-push-link')); ?>
			</div>
		</div>
		<div class="box-content">
			<div class="shadow-box">
				<table class="data">
					<tr>
						<th>Name</th>
						<td><?php echo $entry->name?></td>
					</tr>
					<tr>
						<th>Email</th>
						<td><?php echo $entry->email?></td>
					</tr>
					<tr>
						<th>Phone</th>
						<td><?php echo $entry->phone?></td>
					</tr>
					<tr>
						<th>Password</th>
						<td><?php echo $entry->password?></td>
					</tr>
					<tr>
						<th>State</th>
						<td><?php echo $entry->state()?></td>
					</tr>
					<tr>
						<th>Description</th>
						<td><?php echo nl2br($entry->description)?></td>
					</tr>
					<tr>
						<th>Added with</th>
						<td><?php echo $entry->getAddedWith()?></td>
					</tr>
					<tr>
						<th>Register Date</th>
						<td><?php echo $entry->created()?></td>
					</tr>
					<?php if ($entry->updated):?>
						<tr>
							<th>Update Date</th>
							<td><?php echo $entry->updated()?></td>
						</tr>
					<?php endif?>
				</table>
			</div>
		</div>
	</section>
</div>