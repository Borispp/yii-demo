<?php $form = $this->beginWidget('YsaMemberForm'); ?>

<div class="search-bar">
    <?php foreach ($searchOptions as $optName => $optValue) : ?>
        <div class="box">
            <?php echo YsaHtml::label($optValue['label'], 'search-bar-' . $optName); ?>
            <?php
				$name = 'Fields[' . $optName . ']';
			
                switch ($optValue['type']) {
                    case YsaSearchBar::TYPE_TEXT:
                        echo YsaHtml::textField($name, $optValue['value'], array(
							'id' => 'search-bar-' . $optName,
						));
                        break;
                    case YsaSearchBar::TYPE_SELECT:
                        echo YsaHtml::dropDownList($name, $optValue['value'], $optValue['options'], array(
							'id' => 'search-bar-' . $optName,
						));
                        break;
                    case YsaSearchBar::TYPE_CHECKBOX:
                        echo YsaHtml::checkBox($name, (int) $optValue['value'], array(
							'id' => 'search-bar-' . $optName,
						));
                        break;
                    case YsaSearchBar::TYPE_CALENDAR:
                        echo YsaHtml::textField($name, $optValue['value'], array(
							'id'	=> 'search-bar-' . $optName,
							'class'	=> 'calendar',
						));
                        break;
                }
            ?>
        </div>
    <?php endforeach; ?>
    <div class="box submit">
		<?php echo YsaHtml::hiddenField(YsaSearchBar::FIELD_BAR_NAME); ?>
		<?php echo YsaHtml::hiddenField(YsaSearchBar::FIELD_RESET_NAME, 0); ?>
        <?php echo YsaHtml::submitButton('Search'); ?>
		<?php echo YsaHtml::button('Reset', array(
			'id' => YsaSearchBar::BUTTON_RESET_ID
		)); ?>
    </div>
</div>

<script type="text/javascript">
	$(function(){
		$('#<?php echo YsaSearchBar::BUTTON_RESET_ID?>').click(function(){
			var form = $(this).parents('form');
			form.find('[name=<?php echo YsaSearchBar::FIELD_RESET_NAME?>]').val(1);
			form.submit();
		})
	})
</script>

<?php $this->endWidget(); ?>