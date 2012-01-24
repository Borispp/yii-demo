<section class="w box" id="event-list">
	<div class="box-title">
		<h3>Events</h3>
		<div class="box-title-button">
			<?php echo YsaHtml::link('<span class="icon i_plus_alt"></span>Create New Event', array('create'), array('class' => 'secondary iconed')); ?>
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
						<th class="w_30">Name</th>
						<th>Description</th>
						<th class="w_1">Type</th>
						<th class="w_1">State</th>
						<th class="actions">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php if (count($entries)) : ?>
						<?php foreach ($entries as $entry) : ?>
								<tr class="state-<?php echo strtolower($entry->state()); ?>">
									<td><?php echo $entry->id; ?></td>
									<td>
										<?php echo YsaHtml::link('<strong>' . $entry->name . '</strong>', array('event/view/' . $entry->id), array('class' => 'title')); ?>

										<span class="descr">Password: <strong><?php echo $entry->passwd; ?></strong></span>
									</td>
									<td>
										<?php echo $entry->description; ?>
									</td>
									<td>
										<span class="<?php echo strtolower($entry->type()); ?>"><?php echo $entry->type(); ?></span>
									</td>
									<td>
										<span class="<?php echo strtolower($entry->state()); ?>"><?php echo $entry->state(); ?></span>
									</td>
									<td class="actions">
										<?php echo YsaHtml::link('View', array('event/view/' . $entry->id), array('class' => 'icon i_wrench', 'title' => 'View Event')); ?>
										&nbsp;
										<?php echo YsaHtml::link('Edit', array('event/edit/' . $entry->id), array('class' => 'icon i_brush', 'title' => 'Edit Event')); ?>
										&nbsp;
										<?php echo YsaHtml::link('Delete', array('event/delete/' . $entry->id), array('class' => 'delete icon i_x_alt', 'title' => 'Delete Event')); ?>
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
			<?php $this->widget('YsaMemberPager',array('pages'=>$pagination)) ?>
		</div>
		<div class="cf"></div>
	</div>
</section>