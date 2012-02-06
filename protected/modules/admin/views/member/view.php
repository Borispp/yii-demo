<div class="g12 tab">
    
	<?php echo $this->renderPartial('/_messages/save');?>
	
	<ul>
		<li><a href="#tab-general">General</a></li>
		<li><a href="<?php echo !empty($member->clients) ? '#tab-clients' : '' ?>">Clients</a></li>
		<li><a href="<?php echo !empty($member->events) ? '#tab-events' : '' ?>">Events</a></li>
		<li><a href="<?php echo !empty($member->comments) ? '#tab-comments' : '' ?>">Comments</a></li>
		<li><a href="<?php echo is_object($member->application) ? Chtml::normalizeUrl(array('studio/partialView', 'id'=> $member->application->id)) : '' ?>">Studio</a></li>
	</ul>
        
	<div id="tab-general">
		
		<div class="g12">
		<h3>Quick Information</h3>

		<div class="g12">
			<ul>
				<li><span><?php echo count($member->events); ?></span> Events</li>
				<li><span><?php echo count($member->clients)?></span> Clients</li>
			</ul>
		</div>
		</div>
		
		<div class="g12">
		<h3>Application</h3>

		<?php if ($member->application) : ?>
			<p><?php echo Chtml::link($member->application->name, array('application/moderate/id/'.$member->application->id)) ?> &mdash;
			<?php echo $member->application->statusLabel() ?></p>
		<?php else : ?>
			<p>Application not created</p>
		<?php endif ?>
		
		</div>
		
	</div>
	
	<div id="tab-clients" class="g12">

		<?php if (!empty($member->clients)) : ?>
		
		<table class="data">
		<thead>
			<tr>
				<th class="w_1">ID</th>
				<th class="w_5">Name</th>
				<th class="w_5">Email</th>
				<th class="w_5">Phone</th>
				<th class="w_1">State</th>
				<th class="w_5">Created</th>
				<th class="w_5">Updated</th>
				<th class="w_5">Facebook ID</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($member->clients as $client) : ?>
			<tr>
				<td><?php echo $client->id; ?></td>
				<td><?php echo $client->name; ?></td>
				<td><?php echo $client->email; ?></td>
				<td><?php echo $client->phone; ?></td>
				<td><?php echo $client->state; ?></td>
				<td><?php echo $client->created; ?></td>
				<td><?php echo $client->updated; ?></td>
				<td><?php echo $client->facebook_id; ?></td>
			</tr>
		<?php endforeach ?>
		</tbody>
		</table>
		
		<?php endif; ?>
		
	</div>
	
	<div id="tab-events">
		
		<?php if ($member->events) : ?>

		<table class="data">
		<thead>
			<tr>
				<th class="w_1">ID</th>
				<th class="w_1">Type</th>
				<th class="w_5">Name</th>
				<th class="w_5">Description</th>
				<th class="w_5">Date</th>
				<th class="w_1">State</th>
				<th class="w_5">Modification dates</th>
				<th class="w_5">Albums</th>

			</tr>
		</thead>
		<tbody>
		<?php foreach($member->events as $event) : ?>
			<tr>
				<td><?php echo $event->id; ?></td>
				<td><?php echo $event->type(); ?></td>
				<td><?php echo $event->name; ?></td>
				<td><?php echo $event->description; ?></td>
				<td><?php echo $event->date; ?></td>
				<td><?php echo $event->state(); ?></td>
				<td><span title="Created"><?php echo $event->created; ?></span><br<span title="Updated"><?php echo $event->updated; ?></span></td>
				<td>
					<?php foreach($event->albums as $album) : ?>
						<?php echo $album->name ?>&nbsp;(<span title="Count of photos"><?php echo count($album->photos) ?></span>)
					<?php endforeach; ?>
				</td>

			</tr>
		<?php endforeach ?>
		</tbody>
		</table>
		
		<?php endif ?>
		
	</div>
	
	
	<div id="tab-comments">
		
		<?php if ($comments) : ?>
		
			<table class="data">
			<thead>
				<tr>
					<th class="w_1">ID</th>
					<th class="w_5">Comment</th>
					<th class="w_5">Photo ID</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($comments as $comment) : ?>
				<tr>
					<td><?php echo $comment->id ?></td>
					<td><?php echo $comment->comment ?></td>
					<td><?php echo $comment->photo->id ?></td>
				</tr>
			<?php endforeach ?>
			</tbody>
			</table>
		
		<?php endif ?>
		
	</div>
    
	<div id="tab-studio">
		
		
	</div>	
	
</div>