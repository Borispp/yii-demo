<?php
class YsaController extends CController
{
	protected $_renderVars = array();

	protected $_metaTitle = array();

	protected $_metaTitleDelim = ' | ';

	protected $_metaDescription;

	protected $_metaKeywords;

	public function init() {
		parent::init();
		// disable yii debug toolbar on ajax uploads.
		if (YII_DEBUG && isset($_FILES['file'])) {
			foreach (Yii::app()->log->routes as $route) {
				if ($route instanceof YiiDebugToolbarRoute) {
					$route->enabled = false;
				}
			}
		}
	}
	
	public function setSuccess($message)
	{
		return Yii::app()->user->setFlash('success', $message);
	}

	public function setNotice($message)
	{
		return Yii::app()->user->setFlash('notice', $message);
	}

	public function setError($message)
	{
		return Yii::app()->user->setFlash('error', $message);
	}

	public function sendJson($data)
	{
		echo CJSON::encode($data);
		Yii::app()->end();
	}

	public function sendJsonSuccess($data = array())
	{
		$this->sendJson(array_merge(array('success' => 1), $data));
	}

	public function sendJsonError($data = array())
	{
		$this->sendJson(array_merge(array('error' => 1), $data));
	}

	public function setMeta($meta)
	{
		$this->setMetaTitle($meta->title)
				->setMetaKeywords($meta->keywords)
				->setMetaDescription($meta->description);

		return $this;
	}

	public function clearMeta()
	{
		$this->_metaTitle = array();
		$this->_metaKeywords = '';
		$this->_metaDescription = '';
	}

	public function setMetaTitle($title, $append = false)
	{
		if ($append) {
			array_push($this->_metaTitle, $title);
		} else {
			array_unshift($this->_metaTitle, $title);
		}

		return $this;
	}

	public function setMetaDescription($description)
	{
		$this->_metaDescription = $description;

		return $this;
	}

	public function setMetaKeywords($keywords)
	{
		$this->_metaKeywords = $keywords;

		return $this;
	}

	public function setMetaTitleDelim($delim)
	{
		$this->_metaTitleDelim = $delim;
	}

	public function getMetaTitle($implode = true)
	{
		return $implode ? implode($this->_metaTitleDelim, $this->_metaTitle) : $this->_metaTitle;
	}

	public function getMetaDescription()
	{
		return $this->_metaDescription;
	}

	public function getMetaKeywords()
	{
		return $this->_metaKeywords;
	}

	public function getMetaTitleDelim()
	{
		return $this->_metaTitleDelim;
	}

	public function isMemberPanel()
	{
		if (null === $this->module) {
			return false;
		} elseif ('member' === $this->module->getName()) {
			return true;
		}
		return false;
	}

	public function isAdminPanel()
	{
		if (null === $this->module) {
			return false;
		} elseif ('admin' === $this->module->getName()) {
			return true;
		}
		return false;
	}
	
	public function isWebsite()
	{
		if (null === $this->module) {
			return true;
		} else {
			return false;
		}
	}

	public function getWebsiteNavigationMenu()
	{
		if ($this->isWebsite()) {
			$nav = array(
				array('label'=>'Home', 'url'=>Yii::app()->homeUrl),
				array('label'=>'About', 'url'=> array('about/')),
				array('label'=>'Contact', 'url'=>array('contact/')),
				array('label'=>'Blog', 'url'=>array('blog/')),
				array('label'=>'Tour', 'url'=>array('tour/')),
				array('label'=>'Panel', 'url'=>array('member/'), 'visible' => !Yii::app()->user->isGuest),
			);
		} else {
			$nav = array(
				array('label'=>'Home', 'url'=>Yii::app()->homeUrl),
				array('label'=>'Application', 'url'=>array('application/')),
				array('label'=>'Studio', 'url'=>array('studio/')),
				array('label'=>'Events', 'url'=>array('event/')),
				array('label'=>'Portfolio', 'url'=>array('portfolio/')),
				array('label'=>'Settings', 'url'=>array('settings/')),
			);
		}

		return $nav;
	}

	/**
	 * Render extension with ability to add template vars before render
	 * @param $view
	 * @param array|null $params
	 * @return void
	 */
	public function render($view, array $params = array())
	{
		parent::render($view, $this->_renderVars+$params);
	}

	/**
	 * Add var to all templates
	 * @param $name
	 * @param $value
	 * @return void
	 */
	public function renderVar($name, $value)
	{
		$this->_renderVars[$name] = $value;
	}
	
	public function loadSwfUploader()
	{
//		Yii::app()->getClientScript()
//				  ->registerScriptFile(Yii::app()->baseUrl . '/resources/js/swfupload/swfupload.js', CClientScript::POS_HEAD)
//				  ->registerScriptFile(Yii::app()->baseUrl . '/resources/js/swfupload/swfobject.js', CClientScript::POS_HEAD)
//				  ->registerScriptFile(Yii::app()->baseUrl . '/resources/js/swfupload/settings.js', CClientScript::POS_HEAD);
		
		Yii::app()->getClientScript()
				  ->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plupload/plupload.full.js', CClientScript::POS_HEAD);
//				  ->registerScriptFile(Yii::app()->baseUrl . '/resources/js/swfupload/swfobject.js', CClientScript::POS_HEAD)
//				  ->registerScriptFile(Yii::app()->baseUrl . '/resources/js/swfupload/settings.js', CClientScript::POS_HEAD);
	}
	
    /**
     * Register web application's resources and meta.
     * @param object $view
     * @return bool
     */
    public function beforeRender($view) 
    {
        parent::beforeRender($view);
        
		if (!$this->isAdminPanel()) {
			$this->setMetaTitle(Yii::app()->settings->get('site_title'));

			$clientScript = Yii::app()->getClientScript();

			$clientScript->registerCoreScript('jquery')
						->registerMetaTag($this->getMetaDescription(), 'description')
						->registerMetaTag($this->getMetaKeywords(), 'keywords')
						->registerScriptFile(Yii::app()->baseUrl . '/resources/js/modernizr-2.0.6.js', CClientScript::POS_HEAD)
						->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins.js', CClientScript::POS_HEAD)
						->registerScriptFile(Yii::app()->baseUrl . '/resources/js/screen.js', CClientScript::POS_HEAD)
						->registerCssFile(Yii::app()->baseUrl . '/resources/css/style.css');

		}
		
		if ($this->isWebsite()) {
			$clientScript->registerScriptFile(Yii::app()->baseUrl . '/resources/js/front.js', CClientScript::POS_HEAD)
						->registerCssFile(Yii::app()->baseUrl . '/resources/css/front.css');
			
		} elseif ($this->isMemberPanel()) {
			
			$clientScript->registerScriptFile(Yii::app()->baseUrl . '/resources/js/jquery-ui.min.js', CClientScript::POS_HEAD)
						->registerScriptFile(Yii::app()->baseUrl . '/resources/js/member.js', CClientScript::POS_HEAD)
						->registerCssFile(Yii::app()->baseUrl . '/resources/css/ui/jquery-ui.css')
						->registerCssFile(Yii::app()->baseUrl . '/resources/css/member.css');
		}
        
        return true;
    }
}