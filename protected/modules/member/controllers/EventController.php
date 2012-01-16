<?php
class EventController extends YsaMemberController
{
	public function init()
	{
		parent::init();
		$this->crumb('Events', array('event/'));
	}

	public function actionIndex()
	{
		if (isset($_POST['Fields'])) {
			if (isset($_POST['SearchBarReset']) && $_POST['SearchBarReset']) {
				Event::model()->resetSearchFields();
			} else {
				Event::model()->setSearchFields($_POST['Fields']);
			}
			$this->redirect(array('event/'));
		}

		$criteria = Event::model()->searchCriteria();

		// load only current user's events
		$criteria->addSearchCondition('user_id', $this->member()->id);

		$pagination = new CPagination(Event::model()->count($criteria));
		$pagination->pageSize = Yii::app()->params['admin_per_page'];
		$pagination->applyLimit($criteria);

		$entries = Event::model()->findAll($criteria);

		$this->setMemberPageTitle('Events');
		
		$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/resources/js/member/eventlist.js', CClientScript::POS_HEAD);

		$this->render('index',array(
				'entries'       => $entries,
				'pagination'    => $pagination,
				'searchOptions' => Event::model()->searchOptions(),
			));
	}

	public function actionView($eventId)
	{
		$entry = Event::model()->findByIdLogged($eventId);

		if (!$entry) {
			$this->redirect(array('event/'));
		}

		$this->crumb($entry->name);

		$this->setMemberPageTitle($entry->name);

		$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/resources/js/member/eventpage.js', CClientScript::POS_HEAD);

		$this->render('view', array(
				'entry' => $entry,
			));
	}

	public function actionCreate()
	{
		$entry = new Event();

		if (isset($_POST['Event'])) {

			$entry->attributes = $_POST['Event'];
			$entry->user_id = $this->member()->id;

			// generate password if not set
			if (!$entry->passwd && !$entry->isPortfolio()) {
				$entry->generatePassword();
			}

			$entry->date = YsaHelpers::formatDate($entry->date, Event::FORMAT_DATE);

			if ($entry->validate())
			{
				$entry->save();

				// create default proofing album for proof event
				if (Event::TYPE_PROOF === $entry->type) {
					$album = new EventAlbum();
					$album->setAttributes(array(
							'event_id'  => $entry->id,
							'name'      => EventAlbum::PROOFING_NAME,
							'state'     => EventAlbum::STATE_ACTIVE,
						));
					$album->save();
				}
				$this->redirect(array('event/view/' . $entry->id));
			}
		}

		$this->crumb('Create New Event');

		$this->setMemberPageTitle('Create New Event');

		$this->render('create', array(
			'entry' => $entry,
		));
	}

	public function actionEdit($eventId)
	{
		$entry = Event::model()->findByIdLogged($eventId);

		if (!$entry) {
			$this->redirect(array('event/'));
		}

		if (isset($_POST['Event'])) {
			$entry->attributes = $_POST['Event'];

			if (!$entry->passwd) {
				$entry->generatePassword();
			}

			if ($entry->validate()) {
				$entry->save();

				$this->redirect(array('event/view/' . $entry->id));
			}
		}

		$this->crumb($entry->name);

		$this->setMemberPageTitle($entry->name);

		$this->render('edit', array(
				'entry' => $entry,
			));
	}

	public function actionDelete($eventId = 0)
	{
		$ids = array();
		if (isset($_POST['ids']) && count($_POST['ids'])) {
			$ids = $_POST['ids'];
		} elseif ($eventId) {
			$ids = array($eventId);
		}

		foreach ($ids as $id) {
			$entry = Event::model()->findByIdLogged(intval($id));
			if ($entry) {
				$entry->delete();
			}
		}

		if (Yii::app()->getRequest()->isAjaxRequest) {
			$this->sendJsonSuccess();
		} else {
			$this->redirect(array('event/'));
		}
	}
}