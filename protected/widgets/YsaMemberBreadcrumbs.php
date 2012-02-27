<?php
class YsaMemberBreadcrumbs extends CWidget
{
	public $tagName = 'section';
	
	public $tagList = 'ul';
	
	public $tagElement = 'li';
	
	public $htmlOptions=array('class'=>'breadcrumb w');
	
	public $encodeLabel=true;
	
	public $homeLink;
	
	public $links=array();
	
	public $separator = '//';
	
	public function init() {
		parent::init();
		
		$this->homeLink = YsaHtml::link('Panel', array('/member'));
	}
	
	public function run()
	{
		if(empty($this->links)) {
			echo '<div class="empty-breadcrumb"></div>';
			return;
		}
		
		$links=array();
		if(isset($this->homeLink) && $this->homeLink !== false) {
			$links[]=YsaHtml::openTag($this->tagElement) . $this->homeLink . YsaHtml::closeTag($this->tagElement);
			if ($this->separator) {
				$links[] = YsaHtml::openTag($this->tagElement, array('class' => 'separator')) . $this->separator . YsaHtml::closeTag($this->tagElement);
			}
		}

		echo YsaHtml::openTag($this->tagName,$this->htmlOptions)."\n";
		echo YsaHtml::openTag($this->tagList)."\n";
		
		foreach ($this->links as $k => $link) {	
			if (isset($this->links[$k+1])) {
				if (isset($link['url'])) {
					$lnk = YsaHtml::link($this->encodeLabel ? YsaHtml::encode($link['label']) : $link['label'], $link['url']);
				} else {
					$lnk = YsaHtml::openTag('span') . ($this->encodeLabel ? YsaHtml::encode($link['label']) : $link['label']) . YsaHtml::closeTag('span');
				}
			} else {
				$lnk = YsaHtml::openTag('span') . ($this->encodeLabel ? YsaHtml::encode($link['label']) : $link['label']) . YsaHtml::closeTag('span');
			}
			$links[] = YsaHtml::openTag($this->tagElement) . $lnk . YsaHtml::closeTag($this->tagElement);
			if ($this->separator && isset($this->links[$k+1])) {
				$links[] = YsaHtml::openTag($this->tagElement, array('class' => 'separator')) . $this->separator . YsaHtml::closeTag($this->tagElement);
			}
		}
		
		echo implode('', $links);
		
		
		echo YsaHtml::closeTag($this->tagList);
		echo YsaHtml::closeTag($this->tagName);
	}
}