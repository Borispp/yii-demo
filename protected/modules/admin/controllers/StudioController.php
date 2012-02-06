<?php
class StudioController extends YsaAdminController
{
	/*
	public function actionIndex()
	{
		$criteria = new CDbCriteria;

		$pagination = new CPagination(Studio::model()->count($criteria));
		$pagination->pageSize = Yii::app()->params['admin_per_page'];
		$pagination->applyLimit($criteria);

		$entries = Studio::model()->findAll($criteria);

		$this->setContentTitle('Studios list');
		$this->setContentDescription('view all studios.');

		$this->render('index',array(
				'entries'   => $entries,
				'pagination'=> $pagination,
			));
	}

	public function actionView($id)
	{
		$entry = Studio::model()->findByPk((int) $id);

		if (!$entry) {
			$this->redirect('/admin/' . $this->getId());
		}

		$this->setContentTitle($entry->name);

		$this->render('view',array(
			'entry'		=> $entry,
			'links'		=> $entry->links,
			'persons'	=> $entry->persons
		));
	}
	*/
	
	public function actionPartialView($id)
	{
		$entry = Studio::model()->findByPk((int) $id);

		if (!$entry) {
			$this->redirect('/admin/' . $this->getId());
		}

		$this->renderPartial('view',array(
			'entry'		=> $entry,
			'links'		=> $entry->links,
			'persons'	=> $entry->persons
		));
	}
}