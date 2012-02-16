<section id="event" data-eventid="<?php echo $entry->id; ?>" class="w">
	<section class="box">
		<div class="box-title">
			<h3><?php echo $entry->name; ?></h3>
			<div class="box-title-button">
				<?php echo YsaHtml::link('<span class="icon i_bell"></span>Send Push Notification', array('notification/new/recipient/'.$entry->id.'/type/event'), array('class' => 'secondary iconed', 'id' => 'send-push-link')); ?>
				<?php echo YsaHtml::link('<span class="icon i_pencil"></span>Edit Event', array('event/edit/' . $entry->id), array('class' => 'secondary iconed')); ?>
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
						
						<?php if ($this->member()->smugmugAuthorized()) : ?>
							<?php echo YsaHtml::link('Import SmugMug Album', '#album-import-smugmug-container', array('class' => 'btn fancybox fancybox.inline', 'id' => 'album-smugmug-import-button')); ?>
						<?php endif; ?>
						
						<?php if ($this->member()->zenfolioAuthorized()) : ?>
							<?php echo YsaHtml::link('Import ZenFolio Album', '#album-import-zenfolio-container', array('class' => 'btn fancybox fancybox.inline', 'id' => 'album-zenfolio-import-button')); ?>
						<?php endif; ?>
						
						<?php if ($this->member()->passApiLinked()) : ?>
							<?php echo YsaHtml::link('Import PASS Album', '#album-import-pass-container', array('class' => 'btn fancybox fancybox.inline', 'id' => 'album-pass-import-button')); ?>
						<?php endif; ?>
						
					<?php endif; ?>
						
					<div class="cf"></div>
				</div>
					
				<div class="cf"></div>
					<ul id="event-albums" class="albums cf">
						<?php foreach ($entry->albums as $album) : ?>
							<?php $this->renderPartial('/album/_listalbum', array(
								'album' => $album,
								'event' => $entry,
							));?>
						<?php endforeach; ?>
					</ul>
			</div>
			<div class="cf"></div>
		</div>
		
		
		<?php if ($this->member()->smugmugAuthorized()) : ?>
			<section id="album-import-smugmug-container" class="smugmug-album-import album-import box">
				<div class="box-title">
					<h3>Import SmugMug Album</h3>
				</div>
				<div class="box-content">
					<div class="data">
						<select name="album" id="smugmug-album-id">
							<option value="">&ndash;&ndash;&ndash;</option>
							<?php foreach ($this->member()->smugmug()->albums_get('Extras=ImageCount') as $album) : ?>
								<option value="<?php echo $album['id']; ?>|<?php echo $album['Key']; ?>"><?php echo $album['Title']; ?> (<?php echo $album['ImageCount']?>)</option>
							<?php endforeach; ?>
						</select>
						<input type="button" value="Import Album" />
					</div>
					<div class="loading">Importing album&#133; Please be patient &ndash; it takes a while&#133;</div>
				</div>
			</section>
		<?php endif;?>
		
		<?php if ($this->member()->zenfolioAuthorized() && isset($zenfolioHierarchy)) : ?>
			<section id="album-import-zenfolio-container" class="zenfolio-album-import album-import box">
				<div class="box-title">
					<h3>Import ZenFolio Album</h3>
				</div>
				<div class="box-content">
					<div class="data">
						<select name="album" id="zenfolio-album-id">
							<option value="">&ndash;&ndash;&ndash;</option>
							<?php foreach ($zenfolioHierarchy['Elements'] as $element) : ?>
								<option value="<?php echo $element['Id']; ?>"><?php echo $element['Title']; ?> (<?php echo $element['PhotoCount']?>)</option>
							<?php endforeach; ?>
						</select>
						<input type="button" value="Import Album" />
					</div>
					<div class="loading">Importing album&#133; Please be patient &ndash; it takes a while&#133;</div>
				</div>
			</section>
		<?php endif;?>
		
		<?php if ($this->member()->passApiLinked()) : ?>
			<section id="album-import-pass-container" class="pass-album-import album-import box">
				<div class="box-title">
					<h3>Import PASS Album</h3>
				</div>
				<div class="box-content">
					<div class="data">
						<select name="album" id="pass-album-id">
							<option value="">&ndash;&ndash;&ndash;</option>
							<?php foreach ($this->member()->passApi()->ysaAlbumList() as $album) : ?>
								<option value="<?php echo $album->key; ?>"><?php echo $album->title; ?> (<?php echo $album->photo_count ?>)</option>
							<?php endforeach; ?>
						</select>
						<input type="button" value="Import Album" />
					</div>
					<div class="loading">Importing album&#133; Please be patient &ndash; it takes a while&#133;</div>
				</div>
			</section>
		<?php endif;?>
		
	</section>
</section>