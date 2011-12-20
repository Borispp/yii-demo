<li>
	<strong><?php echo $entry->name(); ?></strong><br/>
	<em><?php echo Time::timeAgoInWords($entry->created); ?></em>
	<p><?php echo $entry->comment; ?></p>
</li>