<div class="g12">
	<?php if ($list) : ?>
		<h3>List Information</h3>
		<dl  class="clearfix">
			<dt>List ID</dt>
			<dd><?php echo $list['id']; ?></dd>
			
			<dt>List Name</dt>
			<dd><?php echo $list['name']; ?></dd>
			
			<dt>Date Created</dt>
			<dd><?php echo Yii::app()->dateFormatter->formatDateTime($list['date_created']); ?></dd>
			
			<dt>Subscribe Url</dt>
			<dd><?php echo YsaHtml::link($list['subscribe_url_short'], $list['subscribe_url_short']); ?></dd>
			
			<dt>Subscribe Full Url</dt>
			<dd><?php echo YsaHtml::link($list['subscribe_url_long'], $list['subscribe_url_long']); ?></dd>
			
			<dt>Beamer Address</dt>
			<dd><?php echo YsaHtml::mailto($list['beamer_address'], $list['beamer_address']); ?></dd>
			
			<dt>Total Members</dt>
			<dd><?php echo $list['stats']['member_count']; ?></dd>
			
			<dt>Total Campaigns</dt>
			<dd><?php echo $list['stats']['campaign_count']; ?></dd>
		</dl>
	<?php endif; ?>
	
	<?php if (is_array($subscribers)) : ?>
	
		<h3>Latest Subscribers (<?php echo $subscribers['total']; ?>)</h3>
	
		<table class="data">
			<thead>
				<tr>
					<th class="l">Email</th>
					<th class="w_20">Subscribed</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($subscribers['data'] as $entry) : ?>
					<tr>
						<td class="l">
							<?php echo $entry['email']; ?>
						</td>
						<td>
							<?php echo Yii::app()->dateFormatter->formatDateTime($entry['timestamp']); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
	

</div>