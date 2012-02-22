<?php
class YsaController extends CController
{
	protected $_renderVars = array();

	protected $_metaTitle = array();

	protected $_metaTitleDelim = ' | ';

	protected $_metaDescription;

	protected $_metaKeywords;
	
	/**
	 * @var CClientScript
	 */
	protected $_cs;

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
		
		$this->_cs = Yii::app()->getClientScript();
	}
	
	public function setSuccess($message)
	{
		return Yii::app()->user->setFlash('success', $message);
	}

	public function setNotice($message)
	{
		return Yii::app()->user->setFlash('notice', $message);
	}
	
	public function setInfo($message)
	{
		return Yii::app()->user->setFlash('info', $message);
	}

	public function setError($message)
	{
		return Yii::app()->user->setFlash('error', $message);
	}
	

	public function setStaticSuccess($message)
	{
		return Yii::app()->user->setFlash('staticSuccess', $message);
	}

	public function setStaticNotice($message)
	{
		return Yii::app()->user->setFlash('staticNotice', $message);
	}
	
	public function setStaticInfo($message)
	{
		return Yii::app()->user->setFlash('staticInfo', $message);
	}

	public function setStaticError($message)
	{
		return Yii::app()->user->setFlash('staticError', $message);
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
		$c = $this->getId();
		$a = $this->getAction()->getId();
		
		if ($this->isWebsite()) {
			$nav = array(
				array('label'=>'Home', 'url'=>Yii::app()->homeUrl),
				array('label'=>'Contact', 'url'=>array('contact/'), 'active' => $c == 'page' && $a == 'contact'),
				array('label'=>'Blog', 'url'=>array('blog/')),
				array('label'=>'Faq', 'url'=>array('faq/'), 'active' => $c == 'faq'),
				array('label'=>'Tour', 'url'=>array('tour/'), 'active' => $c == 'tour'),
				array('label'=>'Pricing', 'url'=>array('pricing/'), 'active' => $c == 'pricing'),
				array('label'=>'Panel', 'url'=>array('member/'), 'visible' => Yii::app()->user->isMember(), 'itemOptions' => array('class' => 'panel')),
				array('label'=>'Panel', 'url'=>array('admin/'), 'visible' => Yii::app()->user->isAdmin(), 'itemOptions' => array('class' => 'panel')),
				array('label'=>'Login', 'url'=>array('/login'), 'visible' => Yii::app()->user->isGuest, 'itemOptions' => array('id' => 'navigation-login-link')),
				array('label'=>'Logout', 'url'=>array('/logout'), 'visible' => !Yii::app()->user->isGuest),
			);
		} else {
			$nav = array(
				array('label'=>'Panel', 'url'=> array('/member/'), 'active' => $c == 'default'),
				array('label'=>'Application', 'url'=>array('application/'), 'active' => $c == 'application'),
				array('label'=>'Studio', 'url'=>array('studio/'), 'active' => in_array($c, array('studio', 'link', 'person', 'inbox')), 'items' => array(
					array('label' => 'Inbox', 'url' => array('inbox/'),'active' => ($c == 'inbox') ),
				)),
				array('label'=>'Events', 'url'=>array('event/'), 'active' => in_array($c, array('event', 'album', 'photo'))),
				array('label'=>'Clients', 'url'=>array('client/'), 'active' => $c == 'client'),
				
				array('label'=>'Support', 'url'=>array('support/'), 'active' => in_array($c, array('support', 'help')), 'items' => array(
					array('label' => 'Tutorials', 'url' => array('help/'), 'active' => ($c == 'help') ),
					array('label' => 'Zendesk', 'url' => Yii::app()->settings->get('zendesk_url')),
				)),
				
				array('label'=>'Settings', 'url'=>array('settings/'), 'active' => in_array($c, array('settings', 'announcement')), 'items' => array(
					array('label' => 'Smugmug', 'url' => array('settings/smugmug/'), 'active' => ($a == 'smugmug') ),
					array('label' => 'ZenFolio', 'url' => array('settings/zenfolio/'), 'active' => ($a == 'zenfolio') ),
					array('label' => 'ShootQ', 'url' => array('settings/shootq/'), 'active' => ($a == 'shootq') ),
					array('label' => 'Facebook', 'url' => array('settings/facebook/'), 'active' => ($a == 'facebook') ),
					array('label' => 'PASS', 'url' => array('settings/pass/'), 'active' => ($a == 'PASS') ),
					array('label' => 'Announcements', 'url' => array('announcement/'), 'active' => ($c == 'announcement') ),
				)),
				array('label'=>'Logout', 'url'=>array('/logout'), 'visible' => !Yii::app()->user->isGuest, 'itemOptions' => array('class' => 'logout')),
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
	
	public function loadPlupload()
	{
		$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/plupload/plupload.full.js', CClientScript::POS_END)
				  ->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/plupload/jquery.plupload.queue/jquery.plupload.queue.js', CClientScript::POS_END)
				  ->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/plupload/jquery.ui.plupload/jquery.ui.plupload.js', CClientScript::POS_END);
	
		return $this;
	}
	
	
	public function loadFancybox()
	{
		$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/fancybox.js', CClientScript::POS_END)
				  ->registerCssFile(Yii::app()->baseUrl . '/resources/css/plugins/fancybox.css');
		
		return $this;
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

			$this->_cs->registerCoreScript('jquery')
					->registerMetaTag($this->getMetaDescription(), 'description')
					->registerMetaTag($this->getMetaKeywords(), 'keywords');
		}
		
		if (defined('YII_DEBUG') && YII_DEBUG) {
			$this->_debugBeforeRender();
		} else {
			$this->_productionBeforeRender();
		}
        
        return true;
    }
	
	
	public function _debugBeforeRender()
	{
		if (!$this->isAdminPanel()) {
			$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/modernizr.js', CClientScript::POS_HEAD)
				->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/tools.js', CClientScript::POS_HEAD)
				->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/scrollto.js', CClientScript::POS_HEAD)
				->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/color.js', CClientScript::POS_HEAD)
				->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/form.js', CClientScript::POS_HEAD)
				->registerScriptFile(Yii::app()->baseUrl . '/resources/js/screen.js', CClientScript::POS_HEAD);			
			
		}

		if ($this->isWebsite()) {
			$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/resources/js/front.js', CClientScript::POS_HEAD)
					->registerCssFile(Yii::app()->baseUrl . '/resources/css/front.css')
					->registerCssFile(Yii::app()->baseUrl . '/resources/css/front-responsive.css', 'screen and (max-width: 1100px)');
			
			
			
			
			
			
		} elseif ($this->isMemberPanel()) {
			// register js
			$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/jquery-ui.min.js', CClientScript::POS_HEAD)
					->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/minicolors.js', CClientScript::POS_HEAD)
					->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/quicksearch.js', CClientScript::POS_HEAD)
					->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/multi-select.js', CClientScript::POS_HEAD)
					->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/uniform.js', CClientScript::POS_HEAD)
					->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/apprise.js', CClientScript::POS_HEAD)
					->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/tiptip.js', CClientScript::POS_HEAD)
					->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/jqueryui-timepicker.js', CClientScript::POS_HEAD)
					->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/fancybox.js', CClientScript::POS_HEAD)
					->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/form.js', CClientScript::POS_HEAD)
					->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/json.js', CClientScript::POS_HEAD)
					->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/jstorage.js', CClientScript::POS_HEAD)
					->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/widgets.js', CClientScript::POS_HEAD)
					->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/maxlength.js', CClientScript::POS_HEAD)
					->registerScriptFile(Yii::app()->baseUrl . '/resources/js/member.js', CClientScript::POS_HEAD);
			// register css
			$this->_cs->registerCssFile(Yii::app()->baseUrl . '/resources/css/ui/jquery-ui.css')
					->registerCssFile(Yii::app()->baseUrl . '/resources/css/plugins/uniform.css')
					->registerCssFile(Yii::app()->baseUrl . '/resources/css/plugins/apprise.css')
					->registerCssFile(Yii::app()->baseUrl . '/resources/css/plugins/minicolors.css')
					->registerCssFile(Yii::app()->baseUrl . '/resources/css/plugins/tiptip.css')
					->registerCssFile(Yii::app()->baseUrl . '/resources/css/plugins/fancybox.css')
					->registerCssFile(Yii::app()->baseUrl . '/resources/css/member.css')
					->registerCssFile('https://fonts.googleapis.com/css?family=Candal');
		}
	}
	
	public function _productionBeforeRender()
	{
		if ($this->isWebsite()) {
//			$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/scrollto.js', CClientScript::POS_HEAD)
//					->registerScriptFile(Yii::app()->baseUrl . '/resources/js/compressed/frontplugins.js', CClientScript::POS_HEAD)
//					->registerScriptFile('http://cdn.jquerytools.org/1.2.6/full/jquery.tools.min.js', CClientScript::POS_HEAD)
//					->registerCssFile(Yii::app()->baseUrl . '/resources/css/front.css')
//					->registerCssFile(Yii::app()->baseUrl . '/resources/css/front-responsive.css', 'screen and (max-width: 1100px)');	
			
			
			$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/modernizr.js', CClientScript::POS_HEAD)
				->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/tools.js', CClientScript::POS_HEAD)
				->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/scrollto.js', CClientScript::POS_HEAD)
				->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/color.js', CClientScript::POS_HEAD)
				->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/form.js', CClientScript::POS_HEAD)
				->registerScriptFile(Yii::app()->baseUrl . '/resources/js/screen.js', CClientScript::POS_HEAD);
			
			$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/resources/js/front.js', CClientScript::POS_HEAD)
					->registerCssFile(Yii::app()->baseUrl . '/resources/css/front.css')
					->registerCssFile(Yii::app()->baseUrl . '/resources/css/front-responsive.css', 'screen and (max-width: 1100px)');
			
			
		} elseif ($this->isMemberPanel()) {
			// register main js
			$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/modernizr.js', CClientScript::POS_HEAD)
				->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/tools.js', CClientScript::POS_HEAD)
				->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/scrollto.js', CClientScript::POS_HEAD)
				->registerScriptFile(Yii::app()->baseUrl . '/resources/js/screen.js', CClientScript::POS_HEAD);
			
			//$plugins = array('jquery-ui.min', 'minicolors', 'quicksearch', 'multi-select', 'uniform', 'apprise', 'tiptip', 'jqueryui-timepicker', 'fancybox', 'form', 'json', 'jstorage', 'widgets', 'maxlength');
			
			// register js
			$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/jquery-ui.min.js', CClientScript::POS_HEAD)
					->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/minicolors.js', CClientScript::POS_HEAD)
					->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/quicksearch.js', CClientScript::POS_HEAD)
					->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/multi-select.js', CClientScript::POS_HEAD)
					->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/uniform.js', CClientScript::POS_HEAD)
					->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/apprise.js', CClientScript::POS_HEAD)
					->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/tiptip.js', CClientScript::POS_HEAD)
					->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/jqueryui-timepicker.js', CClientScript::POS_HEAD)
					->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/fancybox.js', CClientScript::POS_HEAD)
					->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/form.js', CClientScript::POS_HEAD)
					->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/json.js', CClientScript::POS_HEAD)
					->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/jstorage.js', CClientScript::POS_HEAD)
					->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/widgets.js', CClientScript::POS_HEAD)
					->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/maxlength.js', CClientScript::POS_HEAD)
					->registerScriptFile(Yii::app()->baseUrl . '/resources/js/member.js', CClientScript::POS_HEAD);
			// register css
			$this->_cs->registerCssFile(Yii::app()->baseUrl . '/resources/css/ui/jquery-ui.css')
					->registerCssFile(Yii::app()->baseUrl . '/resources/css/plugins/uniform.css')
					->registerCssFile(Yii::app()->baseUrl . '/resources/css/plugins/apprise.css')
					->registerCssFile(Yii::app()->baseUrl . '/resources/css/plugins/minicolors.css')
					->registerCssFile(Yii::app()->baseUrl . '/resources/css/plugins/tiptip.css')
					->registerCssFile(Yii::app()->baseUrl . '/resources/css/plugins/fancybox.css')
					->registerCssFile(Yii::app()->baseUrl . '/resources/css/member.css')
					->registerCssFile('https://fonts.googleapis.com/css?family=Candal');
		}
	}
}