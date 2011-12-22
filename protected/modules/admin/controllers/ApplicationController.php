<?php
class ApplicationController extends YsaAdminController
{
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$criteria = new CDbCriteria;

		$pagination = new CPagination(Member::model()->count($criteria));
		$pagination->pageSize = Yii::app()->params['admin_per_page'];
		$pagination->applyLimit($criteria);

		$entries = Application::model()->findAll($criteria);

		$this->setContentTitle('Applications');
		$this->setContentDescription('view all applications.');

		$this->render('index',array(
				'entries'   => $entries,
				'pagination'=> $pagination,
			));
	}

	public function actionView($id)
	{
		$id = (int) $id;

		$entry = Application::model()->findByPk($id);

		if (!$entry) {
			$this->redirect('/admin/' . $this->getId());
		}

		$this->setContentTitle($entry->name);
		$this->setContentDescription($entry->info);

		$this->render('view',array(
				'entry'     => $entry,
				'options'	=> $this->_getLabelifiedOptions($entry)
			));
	}

	protected function _getLabelifiedOptions(Application $obApplication)
	{
		$result = array();
		foreach(Yii::app()->params['studio_options'] as $section => $sectionInfo /*$property => $params*/)
		{
			foreach($sectionInfo as $property => $propertyInfo)
			{
				$value = $obApplication->option($property);
				if (empty($value))
					continue;
				if (!empty($propertyInfo['img']) && !empty($value['url']))
					$value = '<img src="'.$value['url'].'"/>';
				$result[$obApplication->generateAttributeLabel($section)][$propertyInfo['label']] = (!is_null($value) && array_key_exists('values', $propertyInfo) && $propertyInfo['values'][$value]) ? $propertyInfo['values'][$value] : $value;
			}
		}
		return $result;
	}
}