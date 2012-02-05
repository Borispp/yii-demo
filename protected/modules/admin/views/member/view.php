<div class="g12 tab">
    
	<?php echo $this->renderPartial('/_messages/save');?>
	
	<ul>
		<li><a href="#tab-general">General</a></li>
		<li><a href="#tab-clients">Clients</a></li>
		<li><a href="#tab-events">Events</a></li>
		<!-- <li><a href="#tab-comments">Comments</a></li> -->
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
		
	</div>
	
	<div id="tab-clients" class="g12">
		
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
		
	</div>
	
	<div id="tab-events">
		
		<table class="data">
		<thead>
			<tr>
				<th class="w_1">ID</th>
				<th class="w_5">Type</th>
				<th class="w_5">Name</th>
				<th class="w_5">Description</th>
				<th class="w_5">Date</th>
				<th class="w_1">State</th>
				<th class="w_5">Created</th>
				<th class="w_5">Updated</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($member->events as $event) : ?>
			<tr>
				<td><?php echo $event->id; ?></td>
				<td><?php echo $event->type; ?></td>
				<td><?php echo $event->name; ?></td>
				<td><?php echo $event->description; ?></td>
				<td><?php echo $event->date; ?></td>
				<td><?php echo $event->state; ?></td>
				<td><?php echo $event->created; ?></td>
				<td><?php echo $event->updated; ?></td>
			</tr>
		<?php endforeach ?>
		</tbody>
		</table>
		
	</div>
	
	<!--
	<div id="tab-comments">
		
		
	</div>
	-->
    
</div>