<div class="w" id="inbox-list">
	<section class="box">
		<div class="box-title">
			<h3>Inbox</h3>
		</div>
		<div class="box-content">
			<?php $this->widget('YsaSearchBar', array(
				'searchOptions' => $searchOptions,
			));?>
			<div class="data-box shadow-box">
				<table class="data">
					<thead>
					<tr>
						<th>From</th>
						<th>Subject</th>
						<th class="w_15">Date</th>
						<th class="w_1">State</th>
						<th class="actions">Action</th>
					</tr>
					</thead>
					<tbody>
					<?php if (count($entries)) : ?>
						<?php foreach ($entries as $entry) : ?>
							<tr class="<?php echo $entry->unread ? 'unread' : 'read'?>">
								<td class="name"><?php echo YsaHtml::link($entry->name, array('client/view/' . $entry->client->id)); ?></td>
								<td class="subject"><?php echo YsaHtml::link($entry->subject, array('inbox/view/' . $entry->id)); ?></td>
								<td class="date"><?php echo Yii::app()->dateFormatter->formatDateTime($entry->created, 'medium', '') ?></td>
								<td class="state"><?php echo $entry->unread ? 'unread' : ''?></td>
								<td class="actions">
									<?php echo YsaHtml::link('View', array('inbox/view/' . $entry->id), array('class' => 'icon i_eye', 'title' => 'Read Message')); ?>
									&nbsp;
									<?php echo YsaHtml::link('Delete', array('inbox/delete/' . $entry->id), array('class' => 'delete icon i_delete', 'title' => 'Delete Message')); ?>
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