<section id="event" data-eventid="<?php echo $entry->id; ?>" class="w">
	<section class="box">
		<div class="box-title">
			<h3><?php echo $entry->name; ?></h3>
			<div class="box-title-button">
				<?php echo YsaHtml::link('<span class="icon i_chat"></span>Send Push Notification', '/member/notification/new?type=event&recipient='.$entry->id, array('class' => 'secondary iconed', 'id' => 'send-push-link')); ?>
				<?php echo YsaHtml::link('<span class="icon i_brush"></span>Edit Event', array('event/edit/' . $entry->id), array('class' => 'secondary iconed')); ?>
			</div>
		</div>
		<div class="box-content">
			<div class="description shadow-box">
				<?php if ($entry->description) : ?>
					<div class="title">Description</div>
					<p><?php echo $entry->description; ?></p>
				<?php endif; ?>
					
				<div class="title">State</div>
				<p><?php echo YsaHtml::dropDownList('state', $entry->state, $entry->getStates(), array('id' => 'description-state')); ?></p>
					<dl>
						<dt>ID</dt>
						<dd><?php echo $entry->id; ?></dd>
						
						<dt>Password</dt>
						<dd><?php echo $entry->passwd; ?></dd>
						
						<dt>Type</dt>
						<dd><?php echo $entry->type(); ?></dd>
						
						<dt>Date</dt>
						<dd><?php echo $entry->created('medium', null); ?></dd>
					</dl>
			</div>
			
			<div class="main-box">
				<div class="main-box-title">
					<?php if ($entry->isProofing()) : ?>
						<h3>Proofing Album</h3>
					<?php else:?>
						<h3>Albums</h3>
					<?php endif; ?>
					<?php if (!$entry->isProofing()) : ?>
						<?php echo YsaHtml::link('Create New Album', array('album/create/event/' . $entry->id), array('class' => 'btn blue')); ?>
					<?php endif; ?>
						
					<div class="cf"></div>
				</div>
				

					
				<div class="cf"></div>
				<?php if (count($entry->albums)) : ?>
					<ul id="event-albums" class="albums cf">
						<?php foreach ($entry->albums as $album) : ?>
							<li id="event-album-<?php echo $album->id?>" class="<?php echo strtolower($album->state()); ?>">
								<figure>
									<?php echo $album->preview(); ?>
									<figcaption><?php echo YsaHtml::link(YsaHelpers::truncate($album->name, 25), array('album/view/' . $album->id), array('title' => $album->name)); ?></figcaption>
									<span class="menu">
										<?php echo YsaHtml::link('View', array('album/view/' . $album->id), array('class' => 'view icon i_aperture', 'title' => 'View Album')); ?>
										&nbsp;|&nbsp;
										<?php echo YsaHtml::link('Edit', array('album/edit/' . $album->id), array('class' => 'edit icon i_brush', 'title' => 'Edit Album Details')); ?>
										<?php if (!$entry->isProofing()) : ?>
											&nbsp;|&nbsp;
											<?php echo YsaHtml::link('Delete', array('album/delete/' . $album->id), array('class' => 'del icon i_x_alt', 'rel' => $album->id, 'title' => 'Delete Album')); ?>
										<?php endif; ?>
									</span>
									<?php echo YsaHtml::link('Sort', '#', array('class' => 'move icon i_move')); ?>
								</figure>
							</li>
						<?php endforeach; ?>
					</ul>
					
				<?php else:?>
					<div class="empty-list">Empty List</div>
				<?php endif; ?>
					
			</div>
			<div class="cf"></div>
		</div>
	</section>
</section>