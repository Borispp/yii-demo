<?php
class ApplicationController extends YsaMemberController
{
	public $defaultAction = 'view';

	public function init() {
		parent::init();
		$this->setMetaTitle(Yii::t('title', 'application'));
	}

	public function beforeRender($view) {
		parent::beforeRender($view);

		$this->loadPlupload();

		$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/resources/js/member/appwizardpage.js', CClientScript::POS_HEAD);
		$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/resources/js/member/appviewpage.js', CClientScript::POS_HEAD);

		return true;
	}

	public function actionView()
	{
		$app = $this->member()->application;

		if (null === $app or !$app->filled()) {
			$this->redirect(array('application/wizard/'));
		}

		$this->crumb('Application');

		$this->setMemberPageTitle(Yii::t('title', 'application'));

		$this->_cs->registerCssFile(Yii::app()->baseUrl . '/resources/css/ipad.css');

		$this->render('view', array(
			'app' => $app,
		));
	}

	public function actionQuickCreate()
	{
		if (!$this->member()->application)
		{
			$app = new Application();
			$app->user_id = $this->member()->id;
			$app->state = Application::STATE_ACTIVE;
			$app->name = $this->member()->name()."'s App";
			$app->info = 'My shiny new App';
			$app->generateAppKey();
			$app->generatePasswd();

			if ($app->validate()) {
				$app->save();
				$app->fillWithStyle();
			}
		}
		$this->redirect(array('application/pay/'));
	}

	public function actionCreate()
	{
		if ($this->member()->application) {
			$this->redirect(array('application/'));
		}

		$app = new Application();

		if (isset($_POST['Application'])) {
			$app->attributes = $_POST['Application'];

			$app->setAttributes(array(
				'user_id'    => $this->member()->id,
				'state'      => Application::STATE_ACTIVE,
			));

			$app->generateAppKey();
			$app->generatePasswd();

			if ($app->validate()) {
				$app->save();
				$app->fillWithStyle();
				$this->redirect(array('application/wizard/'));
			}
		}

		$this->crumb('Application');
		$this->setMemberPageTitle(Yii::t('title', 'application_create'));

		$this->render('create', array(
			'app'   => $app,
		));
	}

	public function actionEdit()
	{
		$app = $this->member()->application;

		if (!$app) {
			$this->redirect(array('application/create'));
		}

		if (isset($_POST['Application'])) {
			$app->attributes = $_POST['Application'];

			if ($app->validate()) {
				$app->save();
				$this->setSuccess(Yii::t('save', 'application_saved'));
				$this->redirect(array('application/'));
			}
		}

		$this->setMemberPageTitle('Edit Application General Settings');
		$this->crumb('Application', array('application/'))
				->crumb('Edit');

		$this->render('edit', array(
			'app' => $app,
		));
	}

	public function actionSubmit()
	{
		$app = $this->member()->application;

		if (!$app) {
			$this->redirect(array('application/create'));
		}
		if ($app->isPaid()) {
			$app->submit();
			$app->lock();
			Yii::app()->user->setFlash('congrats', 'congrats');
			$this->redirect(array('congratulations'));
		}
		$this->redirect(array('view'));
	}

	/**
	 * @todo Temporary removed filled check
	 */
	public function actionPay()
	{

		$app = $this->member()->application;
		if (!$app)
		{
			$this->redirect(array('application/create'));
		}

//		if ($app->filled()) {
			YsaHelpers::log('ActionPay in app controller', array('app_id' => $app->id));
			$this->redirect(array('payment/choosepayway/type/application/'));
//		}
		$this->redirect(array('view'));
	}

	public function actionAgreement()
	{
		$app = $this->member()->application;
		if (!$app) {
			$this->redirect(array('application/create'));
		}
		if ($app->isPaid()) {
			$this->redirect(array('view'));
		}
		$page = Page::model()->findBySlug('terms-and-conditions');
		$this->render('agreement', array(
			'page'	=> $page
		));
	}

	public function actionUpload($image)
	{
		$app = $this->member()->application;

		if (!$app) {
			$this->sendJsonError(array(
				'msg' => Yii::t('error', 'application_not_found'),
			));
		}

		if (!in_array($image, $app->getAvailableImages())) {
			$this->sendJsonError(array(
				'msg' => Yii::t('error', 'files_no_available_images'),
			));
		}

		$file = CUploadedFile::getInstanceByName('file');

		if (null === $file) {
			$this->sendJsonError(array(
				'msg' => Yii::t('error', 'files_empty'),
			));
		}

		if ($app->editOption($image, $file, Option::TYPE_TEXT, $image == 'logo')) {
			//			if (in_array($image, array('splash_bg_image', 'studio_bg_image', 'generic_bg_image'))) {
			if (in_array($image, array('studio_bg_image', 'generic_bg_image'))) {
				$app->editOption(str_replace('_image', '', $image), 'image');
			}
			$this->sendJsonSuccess(array(
				'html' => $this->renderPartial('/wizard/_image', array(
					'name'	=> $image,
					'image' => $app->option($image),
				), true)
			));
		} else {
			$this->sendJsonError(array(
				'msg' => Yii::t('error', 'standart_error'),
			));
		}
		Yii::app()->end();
	}

	public function actionDelete($image)
	{
		$app = $this->member()->application;

		if (!$app) {
			$this->sendJsonError(array(
				'msg' => Yii::t('error', 'application_not_found'),
			));
		}

		if (!in_array($image, $app->getAvailableImages())) {
			$this->sendJsonError(array(
				'msg' => Yii::t('error', 'files_no_available_images'),
			));
		}

		$app->deleteOption($image);

		$this->sendJsonSuccess(array(
			'image'	=> $image,
			'html' => $this->renderPartial('/wizard/_upload', array(
				'name'	=> $image,
				'image' => $app->option($image),
			), true)
		));
	}

	public function actionWizard()
	{
		$app = $this->member()->application;

		if (null === $app) {
			$this->redirect(array('application/create'));
		}

		$models = array(
			'logo' => new WizardLogo(),
			'colors' => new WizardColors(),
			'fonts' => new WizardFonts(),
			'copyrights' => new WizardCopyrights(),
			'submit' => new WizardSubmit(),
		);

		foreach ($models as $k => $model) {
			$models[$k]->setApplication($app)
					->loadDefaultValues();
		}

		$this->_cs->registerCssFile(Yii::app()->baseUrl . '/resources/css/ipad.css');

		$this->setMemberPageTitle(Yii::t('title', 'application_wizard'));

		$this->render('wizard', array(
			'app' => $app,
			'models' => $models,
		));
	}

	public function actionLoadStep($step)
	{
		if (Yii::app()->request->isAjaxRequest) {

			$app = $this->member()->application;

			if (!$app) {
				$this->sendJsonError(array(
					'msg' => Yii::t('error', 'application_not_found'),
				));
			}

			if (!$app->filterWizardStep($step)) {
				$this->sendJsonError(array(
					'msg' => Yii::t('error', 'invalid_step'),
				));
			}

			$model = 'Wizard' . ucfirst($step);

			$view = '/wizard/' . $step;

			if (!class_exists($model)) {
				$this->sendJsonError(array(
					'msg' => Yii::t('error', 'standart_error'),
				));
			}

			$this->sendJsonSuccess(array(
				'html' => $this->renderPartial($view, array(
					'app' => $app,
				)),
			));

		} else {
			$this->redirect('application/');
		}
	}

	public function actionSaveStep($step)
	{
		if (Yii::app()->request->isAjaxRequest) {
			$app = $this->member()->application;

			if (!$app->filterWizardStep($step)) {
				$this->sendJsonError(array(
					'msg' => Yii::t('error', 'invalid_step'),
				));
			}

			$modelName = 'Wizard' . ucfirst($step);
			if (!class_exists($modelName)) {
				$this->sendJsonError(array(
					'msg' => Yii::t('error', 'standart_error'),
				));
			}

			if ('submit' == $step) {
				$data = array();
				$filled = $app->isProperlyFilled();

				if (is_array($filled)) {
					$data['fields'] = $filled;
					$data['error'] = 1;
					$data['msg'] = $app->generateFillErrorMsg($filled);
				} else {
					if (!$app->filled()) {
						$app->fill();
					}
					
					$data['redirectUrl'] = $this->createAbsoluteUrl('application/');
					$data['success'] = 1;
				}
				$this->sendJson($data);

			} else {
				$model = new $modelName();
				$model->setApplication($app)
						->loadDefaultValues()
						->prepare()
						->save();
				$this->sendJsonSuccess();
			}
		} else {
			$this->redirect('application/');
		}
	}

	public function actionSaveField()
	{
		if (isset($_POST['field']) && isset($_POST['value']) && in_array($_POST['field'], Yii::app()->params['application_ajax_fields']) && Yii::app()->request->isAjaxRequest) {
			$app = $this->member()->application;
			$app->editOption($_POST['field'], $_POST['value']);
			$this->sendJsonSuccess();
		}
		Yii::app()->end();
	}

	public function actionCongratulations()
	{
		if (!Yii::app()->user->hasFlash('congrats')) {
			$this->redirect(array('application/'));
		}

		$page = Page::model()->findBySlug('wizard-congratulations');
		$this->setMemberPageTitle($page->title);
		$this->crumb('Application', array('application/'))
				->crumb($page->title);
		$this->render('congratulations', array(
			'page' => $page,
		));
	}

	public function actionSupport()
	{
		$app = $this->member()->application;

		if (null === $app) {
			$this->redirect(array('application/create'));
		}

		// there are no tickets
		if (!$app->hasSupport() || !$app->ticket()) {
			$this->redirect(array('application/'));
		}

		$reply = new TicketReply();

		if (isset($_POST['TicketReply'])) {
			$reply->attributes = $_POST['TicketReply'];

			$reply->ticket_id = $app->ticket()->id;
			$reply->reply_by = $this->member()->id;

			if ($reply->validate()) {
				$reply->save();
				$this->setSuccess(Yii::t('save', 'application_support_reply_added'));
				$this->refresh();
			}
		}

		$this->setMemberPageTitle(Yii::t('title', 'application_support'));

		$this->crumb('Application', array('application/'))
				->crumb('Support');

		$this->render('support', array(
			'app'		=> $app,
			'ticket'	=> $app->ticket(),
			'reply'		=> $reply
		));
	}

	public function actionLoadTemplate($template = 'dark')
	{
		$app = $this->member()->application;

		if (null === $app) {
			$this->redirect(array('application/create'));
		}

		$styles = $app->getStyles();

		if (in_array($template, array_keys($styles))) {
			$app->default_style = $template;
			$app->fillWithStyle();

			$this->setSuccess(Yii::t('save', 'application_style_loaded', array('{template}' => $styles[$template])));
		}

		$this->redirect(array('application/wizard/'));
	}

	public function actionQuickPreview()
	{
		if (!Yii::app()->request->isAjaxRequest) {
			$this->redirect(array('application/create'));
		}

		$app = $this->member()->application;

		if ($app) {
			$this->renderPartial('_ipad-preview', array('app' => $app));
		} else {
			echo Yii::t('error', 'standart_error');
		}
		Yii::app()->end();
	}
}