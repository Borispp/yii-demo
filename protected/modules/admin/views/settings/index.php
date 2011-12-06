<div class="g12">
    <?php echo $this->renderPartial('/_messages/save');?>
    
    <?php $this->beginWidget('YsaAdminForm', array(
        'id'=>'settings-form',
        'htmlOptions'=>array('enctype' => 'multipart/form-data'),
        'action'=>Yii::app()->createUrl('/admin/settings/delete'),
        'method'=>'post',
    )); ?>

    <p>
        <?php echo CHtml::link('Add New', array('add', 'group' => $group->slug), array('class' => 'btn i_plus icon ysa fr')); ?>
        <span class="clearfix"></span>
    </p>
<?php if (count($group->options())) : ?>
        <table class="data">
            <thead>
                <tr>
                    <th class="w_1"><input type="checkbox" value="<?php echo $entry->id; ?>" class="ids-toggle" /></th>
                    <th class="l w_20">Title</th>
                    <th class="l">Value</th>
                    <th class="w_10">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($group->options() as $entry) : ?>
                    <tr>
                        <td><input type="checkbox" class="del" value="<?php echo $entry->id; ?>" name="ids[]" /></td>
                        <td class="l">
                            <h5><?php echo $entry->title; ?></h5>
                        </td>
                        <td class="l option">
                            <?php echo $entry->renderField(); ?>
                        </td>
                        <td>
                            <?php echo CHtml::link('Edit', array('edit', 'group' => $group->slug, 'id' => $entry->id), array('class' => 'btn small blue')); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p class="c">
            <button class="submit small red icon i_recycle delete-entries fl">Delete Selected</button>

            <button class="submit big ysa save-entries" id="ysa-options-save">Save</button>
            <span class="clearfix"></span>
        </p>


    <script type="text/javascript">
    $(function(){
        $('#ysa-options-save').click(function(e){
            e.preventDefault();
            var _form = $(this).parents('form');
            $(this).parents('form')
                   .attr('action', '<?php echo Yii::app()->createUrl('/admin/settings/group/' . $group->slug)?>')
                   .submit();
        });
        
        $('#settings-form .option-image a.remove').live('click', function(e){
            e.preventDefault();
            
            var fn = $(this);
            
            $.confirm('Are you sure you want to delete this image?', function(){
                $.post('<?php echo Yii::app()->createUrl('/admin/settings/removeImage')?>', {
                    'id':fn.attr('rel')
                }, function(data){
                    if (data.error) {
                        $.alert('Image was not deleted. Please refresh page and try again.')
                        return;
                    }
                    var option = fn.parents('.option');

                    option.html(data.html);
                    option.find('input[type=file]').uniform();
                    
                }, 'json');                
                
            });
            

        });
        
    });
    </script>
    
<?php else:?>
    
    <h4 class="empty-list">Empty list.</h4>
    
<?php endif; ?>

    <?php $this->endWidget(); ?>

    <?php $this->widget('YsaAdminPager',array('pages'=>$pagination)) ?>
</div>