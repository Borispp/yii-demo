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
						<th>Subject</th>
						<th>From</th>
						<th class="w_20">Date</th>
						<th>State</th>
						<th class="actions">Action</th>
					</tr>
					</thead>
					<tbody>
					<?php if (count($entries)) : ?>
						<?php foreach ($entries as $entry) : ?>
						<tr>
							<td><?php echo CHtml::link($entry->name, array('client/view/' . $entry->client->id)); ?></td>
							<td><?php echo CHtml::link($entry->subject, array('inbox/view/' . $entry->id)); ?></td>
							<td><?php echo date('m.d.Y H:i', strtotime($entry->created)) ?></td>
							<td><?php echo $entry->unread ? 'unread' : ''?></td>
							<td class="actions">
								<?php echo YsaHtml::link('View', array('inbox/view/' . $entry->id), array('class' => 'icon i_wrench', 'title' => 'Read Message')); ?>
								&nbsp;
								<?php echo CHtml::link('Delete', array('inbox/delete/' . $entry->id), array('class' => 'delete icon i_x_alt', 'title' => 'Delete Message')); ?>
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