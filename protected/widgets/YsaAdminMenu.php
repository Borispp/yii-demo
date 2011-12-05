<?php
Yii::import('zii.widgets.CMenu');
class YsaAdminMenu extends CMenu 
{
    /**
     * Wrap links in navigation with spans
     * Needed for the admin template
     * @var type string
     */
    public $linkLabelWrapper = 'span';
    
    public $activeCssClass = 'activated';
}