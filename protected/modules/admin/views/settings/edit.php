<div class="g12">
    <div class="form">
        <?php $form=$this->beginWidget('YsaAdminForm', array(
                'id'=>'settings-form',
                'enableAjaxValidation'=>false,
        )); ?>
        <fieldset>
            <section>
                <?php echo $form->labelEx($entry,'name'); ?>
                <div>
                    <?php echo $form->textField($entry,'name', array('size'=>60,'maxlength'=>100, 'class' => 'w_50')); ?>
                    <?php echo $form->error($entry,'name'); ?>
                </div>
            </section>
            
            <section>
                <?php echo $form->labelEx($entry,'title'); ?>
                <div>
                    <?php echo $form->textField($entry,'title', array('size'=>60,'maxlength'=>100, 'class' => 'w_50')); ?>
                    <?php echo $form->error($entry,'title'); ?>
                </div>
            </section>
            
            <section>
                <?php echo $form->labelEx($entry,'type_id'); ?>
                <div>
                    <?php echo $form->dropDownList($entry, 'type_id', $entry->getTypes(), array('class' => 'w_50')); ?>
                    <?php echo $form->error($entry,'type_id'); ?>
                </div>
            </section>
            
            <section>
                <?php echo $form->labelEx($entry,'options'); ?>
                <div>
                    <?php echo $form->textArea($entry,'options', array('class' => 'w_50')); ?>
                    <?php echo $form->error($entry,'options'); ?>
                    <br/><span>Format: value:title,value:title</span>
                </div>
            </section>
            <section>
                <div>
                    <button class="submit">Save</button>
                </div>
            </section>
        </fieldset>
        <?php $this->endWidget(); ?>
    </div>
</div>