<div class="w" id="client-list">
	<section class="box">
		<div class="box-title">
			<h3>Clients</h3>
			<div class="box-title-button">
				<script type="text/javascript" src="<?php echo Yii::app()->baseUrl?>/resources/js/member/notification_button.js"></script>
				<?php echo YsaHtml::link('<span class="icon i_chat"></span>Send Push Notification To All', '/member/notification/new?type=all&recipient=0', array('class' => 'secondary iconed', 'id' => 'send-push-link')); ?>
				<?php echo YsaHtml::link('<span class="icon i_plus_alt"></span>Register New Client', array('add'), array('class' => 'secondary iconed')); ?>
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
							<th class="w_20">Created</th>
							<th class="w_1">State</th>
							<th class="actions">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php if (count($entries)) : ?>
							<?php foreach ($entries as $entry) : ?>
								<tr>
									<td><?php echo $entry->id;?></td>
									<td>
										<?php echo YsaHtml::link('<strong>' . $entry->name . '</strong>', array('client/view/' . $entry->id), array('class' => 'title')); ?>
										<span class="descr">Email: <strong><?php echo $entry->email; ?></strong></span>
										<?php if ($entry->phone) : ?>
											<span class="descr">Phone: <strong><?php echo $entry->phone; ?></strong></span>
										<?php endif; ?>
									</td>
									<td><?php echo $entry->description?></td>
									<td><?php echo $entry->getAddedWith()?></td>
									<td><?php echo $entry->state()?></td>
									<td class="actions">
										<?php echo YsaHtml::link('View', array('client/view/' . $entry->id), array('class' => 'icon i_wrench', 'title' => 'View Client')); ?>
										&nbsp;
										<?php echo YsaHtml::link('Edit', array('client/edit/' . $entry->id), array('class' => 'icon i_brush', 'title' => 'Edit Client')); ?>
										&nbsp;
										<?php echo YsaHtml::link('Delete', array('client/delete/' . $entry->id), array('class' => 'delete icon i_x_alt', 'title' => 'Delete Client')); ?>
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