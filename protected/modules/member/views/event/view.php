<section id="event" data-eventid="<?php echo $entry->id; ?>" class="w">
	
	
	<section class="box">
		<div class="box-title">
			<h3><?php echo $entry->name; ?></h3>
			<div class="box-title-button">
				<?php echo YsaHtml::link('Edit Event', array('event/edit/' . $entry->id), array('class' => 'btn blue')); ?>
			</div>
		</div>
		<div class="box-content">
			
			<div class="description shadow-box">
				<p><?php echo $entry->description; ?></p>

				<p>
					Credentials : ID - <?php echo $entry->id; ?>, Password: <?php echo $entry->passwd; ?>
					<br/>
					<?php echo $entry->state(); ?>
					<br/>
					<?php echo $entry->type(); ?>
					<br />
					date: <?php echo $entry->date; ?>
				</p>
			</div>
			
			<?php if (!$entry->isProofing()) : ?>
				<p><?php echo CHtml::link('Create New Event Album', array('album/create/event/' . $entry->id), array('class' => 'btn')); ?></p>
			<?php endif; ?>
			
			
			<div class="shadow-box">
				<?php if ($entry->isProofing()) : ?>
					<h3>Proofing Album</h3>
				<?php else:?>
					<h3>Albums</h3>
				<?php endif; ?>
					
					
				<?php if (count($entry->albums)) : ?>
					<ul id="event-albums" class="albums cf">
						<?php foreach ($entry->albums as $album) : ?>
							<li id="event-album-<?php echo $album->id?>">
								<figure><?php echo $album->preview(); ?></figure>
								<div class="menu">
									<?php echo YsaHtml::link('View', array('album/view/' . $album->id), array('class' => 'view')); ?>
									<?php echo YsaHtml::link('Edit', array('album/edit/' . $album->id), array('class' => 'edit')); ?>
									<?php if ($entry->isPublic()) : ?>
										<?php echo YsaHtml::link('Delete', array('album/delete/' . $album->id), array('class' => 'delete', 'rel' => $album->id)); ?>
									<?php endif; ?>
								</div>
							</li>
						<?php endforeach; ?>
					</ul>
					
				<?php else:?>
					<div class="empty-list">Empty List</div>
				<?php endif; ?>
					
			</div>
			
				
				
		</div>
		
		
	</section>
	
	








</section>