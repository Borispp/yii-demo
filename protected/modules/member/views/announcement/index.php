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
					</tr>
					</thead>
					<tbody>
					<?php if (count($announcements)) : ?>
						<?php foreach ($announcements as $announcement) : ?>
						<tr>
							<td><?php echo nl2br($announcement->message)?></td>
						</tr>
						<?php endforeach; ?>
						<?php else:?>
					<tr>
						<td class="empty-list">
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