<div class="w" id="announcement-list">
	<section class="box">
		<div class="box-title">
			<h3>Announcements</h3>
		</div>
		<div class="box-content">
			<div class="data-box shadow-box">
				<table class="data">
					<thead>
					<tr>
						<th>Message</th>
						<th class="actions">Mark as read</th>
					</tr>
					</thead>
					<tbody>
					<?php if (count($announcements)) : ?>
						<?php foreach ($announcements as $announcement) : ?>
						<tr>
							<td><?php echo nl2br($announcement->announcement->message)?></td>
							<td class="actions">
								<?php echo YsaHtml::link('Mark as read', array('announcement/MarkRead/id/' . $announcement->announcement->id), array('class' => '', 'title' => 'Read Message')); ?>
							</td>
						</tr>
							<?php endforeach; ?>
						<?php else:?>
					<tr>
						<td colspan="3" class="empty-list">
							No unread announcements
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