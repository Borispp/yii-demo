<?php
Yii::import('zii.widgets.CBreadcrumbs');
class YsaMemberBreadcrumbs extends CBreadcrumbs
{
	public function init() {
		parent::init();
		
		$this->homeLink = YsaHtml::link('Home', array('/member'));
	}
	
	public function run()
	{
		if(empty($this->links)) {
			return;
		}

		echo CHtml::openTag($this->tagName,$this->htmlOptions)."\n";
		
		$links=array();
		if(isset($this->homeLink) && $this->homeLink !== false) {
			$links[]=$this->homeLink;
		}

		foreach ($this->links as $k => $link) {		
			if (isset($this->links[$k+1])) {
				if (isset($link['url'])) {
					$links[] = YsaHtml::link($this->encodeLabel ? YsaHtml::encode($link['label']) : $link['label'], $link['url']);
				} else {
					$links[] = YsaHtml::openTag('span') . ($this->encodeLabel ? CHtml::encode($link['label']) : $link['label']) . YsaHtml::closeTag('span');
				}
			} else {
				$links[] = YsaHtml::openTag('span') . ($this->encodeLabel ? CHtml::encode($link['label']) : $link['label']) . YsaHtml::closeTag('span');
			}
		}
		
		echo implode($this->separator,$links);
		
		echo CHtml::closeTag($this->tagName);
	}
}