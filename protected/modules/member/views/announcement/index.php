<div class="w" id="announcement-list">
	<section class="box">
		<div class="box-title">
			<h3><?php echo Yii::t('title', 'announcement')?></h3>
		</div>
		<div class="box-content">
			<div class="data-box shadow-box">
				<table class="data">
					<thead>
					<tr>
						<th><?php echo Announcement::model()->getAttributeLabel('message')?></th>
						<th><?php echo Announcement::model()->getAttributeLabel('created')?></th>
					</tr>
					</thead>
					<tbody>
					<?php if (count($announcements)) : ?>
						<?php foreach ($announcements as $announcement) : ?>
						<tr>
							<td><?php echo nl2br($announcement->message)?></td>
							<td><?php echo date('m.d.Y H:i', strtotime($announcement->created)) ?></td>
						</tr>
						<?php endforeach; ?>
						<?php else:?>
					<tr>
						<td class="empty-list" colspan="2">
							<?php echo Yii::t('notice', 'no_unread_announcements')?>
						</td>
					</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
			<div class="cf"></div>
		</div>
	</section>
</div>