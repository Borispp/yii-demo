<section class="w box" id="event-list">
	<div class="box-title">
		<h3>Events</h3>
		<div class="box-title-button">
			<?php echo YsaHtml::link('<span class="icon i_round_plus"></span>Create New Event', array('create'), array('class' => 'secondary iconed')); ?>
		</div>
	</div>
	<div class="box-content">
		<?php $this->widget('YsaSearchBar', array(
			'searchOptions' => $searchOptions,
		));?>
		<div class="data-box shadow-box">
			<table class="data" data-control="event">
				<thead>
					<tr>
						<th class="w_1"><input type="checkbox" value="all" name="entries-select-all" id="entries-select-all" /></th>
						<th class="w_1">ID</th>
						<th class="w_30">Name</th>
						<th>Description</th>
						<th class="w_1">Type</th>
						<th class="w_1">State</th>
						<th class="actions">Actions</th>
					</tr>
				</thead>
				<?php if (count($entries)) : ?>
					<tfoot>
						<tr>
							<td colspan="7">
								<?php echo YsaHtml::link('Remove Selected', '#', array('class' => 'btn small entries-delete-selected gray-txt')); ?>
							</td>
						</tr>
					</tfoot>
				<?php endif;?>
				<tbody>
					<?php if (count($entries)) : ?>
						<?php foreach ($entries as $entry) : ?>
								<tr class="state-<?php echo strtolower($entry->state()); ?>" id="entry-row-<?php echo $entry->id?>">
									<td><input type="checkbox" name="entries[<?php echo $entry->id?>]" value="<?php echo $entry->id?>" /></td>
									<td><?php echo $entry->id; ?></td>
									<td>
										<?php echo YsaHtml::link('<strong>' . $entry->name . '</strong>', array('event/view/' . $entry->id), array('class' => 'title')); ?>
										<?php if (!$entry->isPortfolio()) : ?>
											<span class="descr">ID: <strong><?php echo $entry->id; ?></strong>&nbsp;&nbsp;|&nbsp;&nbsp;Password: <strong><?php echo $entry->passwd; ?></strong></span>
										<?php endif; ?>
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
										<?php echo YsaHtml::link('View', array('event/view/' . $entry->id), array('class' => 'icon i_eye', 'title' => 'View Event')); ?>
										&nbsp;
										<?php echo YsaHtml::link('Edit', array('event/edit/' . $entry->id), array('class' => 'icon i_pencil', 'title' => 'Edit Event')); ?>
										&nbsp;
										<?php echo YsaHtml::link('Delete', array('event/delete/' . $entry->id), array('class' => 'delete icon i_delete', 'title' => 'Delete Event')); ?>
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