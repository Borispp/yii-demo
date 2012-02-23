<?php //
class ApplicationController extends YsaAdminController
{
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$app_search = new YsaApplicationSearchForm;
		$app_search->setAttributes(!empty($_GET['YsaApplicationSearchForm']) ? $_GET['YsaApplicationSearchForm'] : array(),false);
		$criteria = $app_search->searchCriteria();

		$pagination = new CPagination(Application::model()->count($criteria));
		$pagination->pageSize = Yii::app()->params['admin_per_page'];
		$pagination->applyLimit($criteria);

		$entries = Application::model()->findAll($criteria);

		$this->setContentTitle('Applications');
		$this->setContentDescription('view all applications.');

		$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/adm/js/search-form.js', CClientScript::POS_HEAD);
		$this->render('index',array(
			'entries'   => $entries,
			'pagination'=> $pagination,
			'app_search' => $app_search,
		));
	}
	
	public function actionDownload($id, $image)
	{
		$id = (int) $id;

		$entry = Application::model()->findByPk($id);

		if (!$entry) {
			$this->redirect('/admin/' . $this->getId());
		}
		
		if (in_array($image, array('icon', 'itunes_logo', 'splash_bg_image'))) {
			
			$value = $entry->option($image);
			
			if (!$value) {
				$this->redirect(array($this->getId() . '/'));
			}
			
			if (!is_file($value['path'])) {
				$this->setError('File not found.');
				$this->redirect(array('application/edit/id/' . $id . '/'));
			}
			
			$img = new YsaImage($value['path']);
			
			if ($image == 'splash_bg_image') {
				$img->auto_crop(1024, 748);
			}
			
			$newImageName = tempnam(sys_get_temp_dir(), 'YSA_' . $image . '_image') . '.' . $img->ext;
			
			$img->save($newImageName);
			
			Yii::app()->request->sendFile($id . '_' . $image . '.png', file_get_contents($newImageName));
			Yii::app()->end();
		}
		$this->redirect(array('application/'));
	}

	public function actionEdit($id)
	{
		$id = (int) $id;

		$entry = Application::model()->findByPk($id);

		if (!$entry) {
			$this->redirect(array($this->getId() . '/'));
		}
		if(Yii::app()->request->isPostRequest && isset($_POST['Application']) && !empty($_POST['Application']['state']))
		{
			$entry->state = $_POST['Application']['state'];
			if($entry->save()) {
				$this->setSuccessFlash("Application status successfully updated. " . CHtml::link('Back to listing.', array('index')));
				$this->refresh();
			}
		}
		
		$this->setContentTitle($entry->name);
		$this->setContentDescription($entry->info);
		
		$this->render('edit', array(
			'entry'			=> $entry,
			'icon'			=> $entry->option('icon'),
			'itunes_logo'	=> $entry->option('itunes_logo')
		));
	}
	
	public function actionReview($id)
	{
		$id = (int) $id;

		$entry = Application::model()->findByPk($id);

		if (!$entry) {
			$this->redirect(array($this->getId() . '/'));
		}
		
		if (isset($_POST['message'])) {
			$ticket = new Ticket();
			
			$ticket->user_id = $entry->user->id;
			$ticket->title = 'Application Ticket' ;
			$ticket->generateCode();
			$ticket->state = Ticket::STATE_ACTIVE;
			$ticket->save();
			
			$reply = new TicketReply();
			$reply->reply_by = Yii::app()->user->id;
			$reply->ticket_id = $ticket->id;
			$reply->message = $_POST['message'];
			$reply->save();
			
			$member = Member::model()->findByPk($ticket->user_id);
			
			$member->notify(
				'New support ticket: ' . $reply->message . ' (' . YsaHtml::link('View support ticket', array('/member/application/support/')) . ')'
			);
			
			$entry->unapproved();
			
			$this->redirect(array('ticket/view/id/' . $ticket->id . '/'));
			
		} elseif (isset($_POST['appstore_link'])) {
			
			$entry->editOption('appstore_link', $_POST['appstore_link']);
			
			if (isset($_POST['notify_member']) && $_POST['notify_member']) {
				$member = Member::model()->findByPk($entry->user->id);	
				$member->notify('Your application was successfully added to AppStore. You preview it ' . YsaHtml::link('here', $_POST['appstore_link'], array('rel' => 'external')));
			}
			$this->setSuccessFlash('AppStore Link has been successfully updated.');
			$this->redirect(array($this->getId() . '/moderate/id/' . $id . '/'));
		} else {
			$this->redirect(array($this->getId() . '/moderate/id/' . $id . '/'));
		}
		
	}
	
	public function actionModerate($id)
	{
		$id = (int) $id;

		$entry = Application::model()->findByPk($id);

		if (!$entry) {
			$this->redirect(array($this->getId() . '/'));
		}
		if(Yii::app()->request->isPostRequest && isset($_POST['Application']) && !empty($_POST['Application']['state']))
		{
			$entry->state = $_POST['Application']['state'];
			if($entry->save()) {
				$this->setSuccessFlash("Application status successfully updated. " . CHtml::link('Back to listing.', array('index')));
				$this->refresh();
			}
		}
		$this->setContentTitle($entry->name);
		$this->setContentDescription($entry->info);
		
		$this->render('moderate', array(
			'entry'			=> $entry,
			'options'		=> $this->_getLabelifiedOptions($entry),
			'icon'			=> $entry->option('icon'),
			'itunes_logo'	=> $entry->option('itunes_logo'),
			'splash'		=> $entry->option('splash_bg_image'),
		));
	}

	protected function _getLabelifiedOptions(Application $obApplication)
	{
		$result = array();
		
		foreach(Yii::app()->params['studio_options'] as $section => $sectionInfo)
		{
			foreach($sectionInfo as $property => $propertyInfo)
			{
				$value = $obApplication->option($property);
				if (empty($value))
					continue;
				if (!empty($propertyInfo['img']) && !empty($value['url'])) {
					$value = YsaHtml::link(YsaHtml::image($value['url']), $value['url'], array(
						'class' => 'fancybox image',
						'title' => $propertyInfo['label'],
						'rel' => 'application-image'));
				} elseif (substr_count($property, '_color')) {
					$value = YsaHtml::openTag('span', array(
						'class' => 'color',
						'style' => 'background-color:' . $value)
					)
					.YsaHtml::closeTag('span')
					.YsaHtml::openTag('span', array('class' => 'lbl')).$value.YsaHtml::closeTag('span');
				}

				$result[$obApplication->generateAttributeLabel($section)][$obApplication->generateAttributeLabel($property)] =
				(!is_null($value) && array_key_exists('values', $propertyInfo) && !empty($propertyInfo['values'][$value]))
					? $propertyInfo['values'][$value]
					: $value;
			}
		}
		
		return $result;
	}
	
	public function actionSetState($id, $mark)
	{
		$id = (int) $id;
		
		$entry = Application::model()->findByPk($id);

		if (!$entry) {
			$this->redirect(array($this->getId() . '/'));
		}
		
		switch ($mark) {
			case 'approve':
				$entry->approve();
				$entry->lock();
				break;
			case 'lock':
				$entry->lock();
				break;
			case 'submit':
				$entry->submit();
				$entry->lock();
				break;
			case 'unapprove':
				$entry->unlock();
				$entry->unapprove();
				break;
			case 'ready':
				$entry->ready();
				break;
			case 'rejected':
				$entry->reject();
				break;
			case 'run':
				$entry->run();
				break;
			case 'restart':
				$entry->restart();
				$entry->deleteOption('appstore_link');
				break;
			default:
				break;
		}
		$msg = "Application status successfully updated.";
		if (Yii::app()->request->isAjaxRequest) {
			$this->sendJsonSuccess(array(
				'msg' => $msg,
			));
		} else {
			$this->setSuccessFlash($msg);
			$this->redirect(array('application/moderate/id/' . $entry->id . '/'));			
		}
	}
	
	public function actionToggleLock($id)
	{
		$id = (int) $id;
		
		$entry = Application::model()->findByPk($id);

		if (!$entry) {
			$this->redirect(array($this->getId() . '/'));
		}
		
		if ($entry->locked()) {
			$entry->unlock();
		} else {
			$entry->lock();
		}
		
		$msg = "Application was " . ($entry->locked() ? 'Locked' : 'Unlocked');
		if (Yii::app()->request->isAjaxRequest) {
			$this->sendJsonSuccess(array(
				'msg' => $msg,
			));
		} else {
			$this->setSuccessFlash($msg);
			$this->redirect(array('application/moderate/id/' . $entry->id . '/'));			
		}
	}
}