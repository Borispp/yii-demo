<?php
class YsaMemberPager extends CLinkPager
{
    public $cssFile = false;
	
	public $htmlOptions = array(
		'class' => 'pager',
		'id'	=> 'data-pager',
	);
	
	public $nextPageLabel = 'Next';
	
	public $prevPageLabel = 'Prev';
	
	public $firstPageLabel = 'First';
	
	public $lastPageLabel = 'Last';
	
	public $header = '';
}
