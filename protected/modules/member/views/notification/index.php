<div class="w" id="notification-list">



	<section class="box">
		<div class="box-title">
			<h3>Notifications</h3>
			<div class="box-title-button">
				<?php echo YsaHtml::link('Add New Notification', array('new'), array('class' => 'btn blue')); ?>
			</div>
		</div>
		<div class="box-content">

			<?php $this->widget('YsaSearchBar', array(
				'searchOptions' => $searchOptions,
			));?>
			
			<div class="data-box shadow-box">
				<table class="data">
					<thead>
						<tr>
							<th class="w_1">ID</th>
							<th>Message</th>
							<th class="w_20">Created</th>
							<th class="actions">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php if (count($entries)) : ?>
							<?php foreach ($entries as $entry) : ?>
								<tr>
									<td><?php echo $entry->id;?></td>
									<td><?php echo YsaHtml::link('<strong>' . nl2br($entry->message) . '</strong>', array('notification/view/' . $entry->id), array('class' => 'title')); ?></td>
									<td><?php echo date('m.d.Y H:i', strtotime($entry->created))?></td>
									<td class="actions">
										<?php echo YsaHtml::link('View', array('client/view/' . $entry->id), array('class' => 'icon i_wrench', 'title' => 'View Notification')); ?>
										&nbsp;
										<?php echo YsaHtml::link('Delete', array('client/delete/' . $entry->id), array('class' => 'delete icon i_x_alt', 'title' => 'Delete Notification')); ?>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else:?>
							<tr>
								<td colspan="6" class="empty-list">
									Empty List
								</td>
							</tr>
						<?php endif; ?>

					</tbody>
				</table>
				<?php $this->widget('YsaAdminPager',array('pages'=>$pagination)) ?>
			</div>
			<div class="cf"></div>
		</div>
	</section>
</div>