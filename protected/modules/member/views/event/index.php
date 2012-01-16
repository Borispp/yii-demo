<section class="w box">
	
	
	
	<div class="box-title">
		<h3>Events</h3>
		<div class="box-title-button">
			<?php echo YsaHtml::link('Create New Event', array('create'), array('class' => 'btn blue primary')); ?>
		</div>
	</div>
	
	<div class="box-content">
		<?php $this->widget('YsaSearchBar', array(
			'searchOptions' => $searchOptions,
		));?>

	

		<table class="data">
			<thead>
				<tr>
					<th>Credentials</th>
					<th>Name</th>
					<th>Date</th>
					<th>Type</th>
					<th>State</th>
					<th>Description</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($entries as $entry) : ?>
					<tr>
						<td>
							ID <?php echo $entry->id; ?><br/>
							Pass <?php echo $entry->passwd; ?>
						</td>
						<td><?php echo $entry->name; ?></td>
						<td><?php echo $entry->date; ?></td>
						<td><?php echo $entry->type(); ?></td>
						<td><?php echo $entry->state(); ?></td>
						<td><?php echo $entry->description; ?></td>
						<td>
							<?php echo YsaHtml::link('View', array('event/view/' . $entry->id), array()); ?> <br />
							<?php echo YsaHtml::link('Edit', array('event/edit/' . $entry->id), array()); ?> <br />
								<?php if (!$entry->isProofing()) : ?>
									<?php echo YsaHtml::link('Add Album', array('album/create/event/' . $entry->id), array()); ?> <br />
								<?php endif; ?>
							<?php echo YsaHtml::link('Delete', array('event/delete/' . $entry->id), array()); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<? $this->widget('YsaAdminPager',array('pages'=>$pagination)) ?>
	</div>
</section>