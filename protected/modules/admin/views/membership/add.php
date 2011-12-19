<div class="g12">
    <div class="form">
        <?php $form=$this->beginWidget('YsaAdminForm', array(
                'id'=>'option-group-form',
                'enableAjaxValidation'=>false,
        )); ?>
        <fieldset>
            <label>General Information</label>
            
            <section>
                <?php echo $form->labelEx($entry,'name'); ?>
                <div>
                    <?php echo $form->textField($entry,'name', array('size'=>60,'maxlength'=>50, 'class' => 'w_50')); ?>
                    <?php echo $form->error($entry,'name'); ?>
                </div>
            </section>
            
            <section>
                <?php echo $form->labelEx($entry,'description'); ?>
                <div>
                    <?php echo $form->textArea($entry,'description', array(
                        'data-autogrow' => 'true',
                        'rows'          => 3,
                    )); ?>
                    <?php echo $form->error($entry,'description'); ?>
                </div>
            </section>
            
            <section>
                <?php echo $form->labelEx($entry,'duration'); ?>
                <div>
                    <?php echo $form->textField($entry,'duration', array('size'=>12,'maxlength'=>12, 'class' => 'w_20')); ?> months
                    <?php echo $form->error($entry,'duration'); ?>
                </div>
            </section>
            <section>
                <?php echo $form->labelEx($entry,'price'); ?>
                <div>
                    <?php echo $form->textField($entry,'price', array('size'=>12,'maxlength'=>12, 'class' => 'w_20')); ?> USD
                    <?php echo $form->error($entry,'price'); ?>
                </div>
            </section>

            <section>
                <?php echo $form->labelEx($entry,'active'); ?>
                <div>
                    <?php echo $form->checkBox($entry,'active', array(
                        'checked' => $entry->active,
                    )); ?>
                    <?php echo $form->error($entry,'active'); ?>
                </div>
            </section>
        </fieldset>
        <?php echo YsaHtml::adminSaveSection();?>
        <?php $this->endWidget(); ?>
    </div>
</div>