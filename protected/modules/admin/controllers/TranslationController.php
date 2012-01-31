<?php
class TranslationController extends YsaAdminController
{
	public function actionIndex($cat)
	{	
		// delete messages
		if (isset($_POST['ids']) && count($_POST['ids'])) {
			$this->forward('delete');
		}
		
		$category = TranslationCategory::model()->findBy('name', $cat);
		
		if (!$category) {
			$this->redirect(array('/admin/'));
		}
		
		if (isset($_POST['source']) && isset($_POST['translation'])) {
			foreach ($_POST['source'] as $k => $message) {
				
				$message = trim($message);
				
				if (!$message) {
					continue;
				}
				
				$source = TranslationSource::model()->findBy('message', $message);
				
				// add new source
				if ($source) {
					$source->deleteTranslations();
				} else {
					$source = new TranslationSource();
					$source->setAttributes(array(
						'category'	=> $category->name,
						'message'	=> $message,
					));
					$source->save();
				}
				
				foreach (Yii::app()->params['languages'] as $lang => $title) {
					$translation = new Translation();
					$translation->setAttributes(array(
						'id'		=> $source->id,
						'language'	=> $lang,
						'translation' => isset($_POST['translation'][$lang][$k]) ? $_POST['translation'][$lang][$k] : '',
					));
					$translation->save();
					unset($translation);
				}
				unset($source);
			}
			
			$this->setSuccessFlash('Translations have been saved successfully.');
			$this->refresh();
		}
		
		$sources = TranslationSource::model()->findAll(array(
			'order' => 'message ASC',
			'condition' => 'category=:category',
			'params' => array(
				'category' => $category->name,
			)
		));
		
		foreach ($sources as &$s) {
			$s->loadTranslations();
		}
		
		$this->setContentTitle('Translations');
		$this->setContentDescription('manage questions.');
		
		$this->render('index', array(
			'sources' => $sources,
		));
	}
	
	public function actionDelete()
	{
		$ids = array();
		if (isset($_POST['ids']) && count($_POST['ids'])) {
			$ids = $_POST['ids'];
		} elseif (isset($_GET['id'])) {
			$ids = array(intval($_GET['id']));
		}

		foreach ($ids as $id) {
			$source = TranslationSource::model()->findByPk($id);
			if ($source) {
				$source->deleteTranslations();
				$source->delete();
			}
		}

		if (Yii::app()->getRequest()->isAjaxRequest) {
			$this->sendJsonSuccess();
		} else {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
	}
}