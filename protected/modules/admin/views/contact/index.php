<div class="g12">
    <?php $this->beginWidget('YsaAdminForm', array(
        'id'=>'contact-form',
        'action'=>Yii::app()->createUrl('/admin/contact/delete'),
        'method'=>'post',
    )); ?>
    
    <table class="data">
        <thead>
            <tr>
                <th class="w_1"><input type="checkbox" value="" class="ids-toggle" /></th>
				<th class="w_10">Created</th>
                <th class="l w_20">Sender</th>
                <th class="l">Message</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($entries as $entry) : ?>
                <tr>
                    <td><input type="checkbox" class="del" value="<?php echo $entry->id; ?>" name="ids[]" /></td>
					<td>
						<?php echo $entry->created('medium', 'short'); ?>
					</td>
                    <td class="l">
						<strong><?php echo $entry->name; ?></strong>
						<br/>
						<?php echo YsaHtml::mailto($entry->email, $entry->email); ?>
					</td>

                    <td class="l">
						<h5><?php echo $entry->subject; ?></h5>
						<?php echo $entry->message(); ?>
					</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p class="fl"><button class="submit small red icon i_recycle delete-entries">Delete Selected</button></p>
	<?php $this->widget('YsaAdminPager',array('pages'=>$pagination)) ?>
	<div class="clearfix"></div>
	
    <?php $this->endWidget(); ?>
	
	
</div>