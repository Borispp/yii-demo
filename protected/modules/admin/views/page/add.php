<div class="g12">
    <div class="form tab">
        <?php $form=$this->beginWidget('YsaAdminForm', array(
                'id'=>'page-form',
                'enableAjaxValidation'=>false,
        )); ?>
        
        <ul>
            <li><a href="#tab-general">General</a></li>
            <li><a href="#tab-meta">Meta</a></li>
        </ul>
        
        <div id="tab-general">
            <fieldset>
                <section>
                    <?php echo $form->labelEx($entry,'title'); ?>
                    <div>
                        <?php echo $form->textField($entry,'title', array('size'=>60,'maxlength'=>100)); ?>
                        <?php echo $form->error($entry,'title'); ?>
                    </div>
                </section>

                <section>
                    <?php echo $form->labelEx($entry,'slug'); ?>
                    <div>
                        <?php echo $form->textField($entry,'slug', array('size'=>60,'maxlength'=>50)); ?>
                        <?php echo $form->error($entry,'slug'); ?>
                    </div>
                </section>

                <section>
                    <?php echo $form->labelEx($entry,'parent_id'); ?>
                    <div>
                        <?php echo $form->dropDownList($entry,'parent_id', $entry->getListTree()); ?>
                        <?php echo $form->error($entry,'parent_id'); ?>
                    </div>
                </section>

                <section>
                    <?php echo $form->labelEx($entry,'type'); ?>
                    <div>
                        <?php echo $form->dropDownList($entry, 'type', $entry->getTypes()); ?>
                        <?php echo $form->error($entry,'type'); ?>
                    </div>
                </section>

                <section>
                    <?php echo $form->labelEx($entry,'state'); ?>
                    <div>
                        <?php echo $form->dropDownList($entry, 'state', $entry->getStates()); ?>
                        <?php echo $form->error($entry,'state'); ?>
                    </div>
                </section>

                <section>
                    <?php echo $form->labelEx($entry,'short'); ?>
                    <div>
                        <?php echo $form->textArea($entry,'short', array(
                            'data-autogrow' => 'true',
                            'rows'          => 5,
                        )); ?>
                        <?php echo $form->error($entry,'short'); ?>
                    </div>
                </section>

                <section>
                    <?php echo $form->labelEx($entry,'content'); ?>
                    <div>
                        <?php echo $form->textArea($entry,'content', array(
                            'class' => 'html',
                            'rows'  => 12,
                        )); ?>
                        <?php echo $form->error($entry,'content'); ?>
                    </div>
                </section>

            </fieldset>
        </div>
        <div id="tab-meta">
            <?php $this->renderPartial('_meta', array(
                'meta' => $entry->meta(),
                'form' => $form,
            )); ?>
        </div>
		<?php echo YsaHtml::adminSaveSection();?>
        <?php $this->endWidget(); ?>
    </div>
</div>