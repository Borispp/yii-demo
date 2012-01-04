<?php
class PortfolioAlbumController extends YsaMemberController
{
	public function actionCreate()
	{
		$entry = new PortfolioAlbum();
		
		if (isset($_POST['PortfolioAlbum'])) {
			
			$entry->attributes = $_POST['PortfolioAlbum'];
			
			$entry->portfolio_id = $this->member()->studio->portfolio->id;
			
			if ($entry->validate()) {
				$entry->save();
				$this->redirect(array('portfolioAlbum/view/' . $entry->id));
			}
		}
		
		$this->crumb('Portfolio', array('portfolio/'))
			 ->crumb('Create Album');
		
		$this->setMemberPageTitle('Create Album');
		
		$this->render('create', array(
			'entry' => $entry,
		));
	}
	
	public function actionEdit($albumId)
	{
		$entry = PortfolioAlbum::model()->findByPk($albumId);
		
		if (!$entry || !$entry->isOwner()) {
			$this->redirect(array('portfolio/'));
		}
		
		if (isset($_POST['PortfolioAlbum'])) {
			$entry->attributes = $_POST['PortfolioAlbum'];
			
			if ($entry->validate()) {
				$entry->save();
				$this->redirect(array('portfolioAlbum/view/' . $entry->id));
			}
		}
		
		$this->crumb('Portfolio', array('portfolio/'))
			 ->crumb('Edit Album');
		
		$this->setMemberPageTitle('Edit Album');
		
		$this->render('edit', array(
			'entry' => $entry,
		));
	}
	
	
	public function actionView($albumId)
	{
		$entry = PortfolioAlbum::model()->findByPk($albumId);
		
		if (!$entry || !$entry->isOwner()) {
			$this->redirect(array('portfolio/'));
		}
		
		$upload = new PhotoUploadForm();
		
		if (Yii::app()->getRequest()->isPostRequest) {
			$upload->photo = CUploadedFile::getInstance($upload, 'photo');
			if ($upload->validate()) {
				
				$photo = new PortfolioPhoto();
				$photo->album_id = $entry->id;
				
				$photo->upload($upload->photo);
				
				$this->refresh();
			}
		}
		
		$this->loadSwfUploader();
		
		$this->crumb('Portfolio', array('portfolio/'))
			 ->crumb($entry->name);
		
		$this->setMemberPageTitle($entry->name);
		
		$this->render('view', array(
			'entry'   => $entry,
			'upload'  => $upload, 
		));
	}
	
	public function actionDelete($albumId = 0)
	{
		$ids = array();
		if (isset($_POST['ids']) && count($_POST['ids'])) {
			$ids = $_POST['ids'];
		} elseif ($albumId) {
			$ids = array(intval($albumId));
		}
		
		foreach ($ids as $id) {
			$entry = PortfolioAlbum::model()->findByPk($id);
			if ($entry && $entry->isOwner()) {
				$entry->delete();	
			}
		}
		
		if (Yii::app()->getRequest()->isAjaxRequest) {
			$this->sendJsonSuccess();
		} else {
			$this->redirect(array('portfolio/'));
		}
	}
	
	public function actionSort($albumId = 0)
	{
		if (Yii::app()->getRequest()->isAjaxRequest) {
			if (isset($_POST['event-album']) && is_array($_POST['event-album'])) {
				foreach ($_POST['event-album'] as $k => $id) {
					$entry = PortfolioAlbum::model()->findByPk($id);
					if ($entry && $entry->isOwner()) {
						$entry->rank = $k + 1;
						$entry->save();
					}
				}
			}
			$this->sendJsonSuccess();
		} else {
			$this->redirect(Yii::app()->homeUrl);
		}
	}
	
	public function actionSetCover($albumId = 0)
	{
		$entry = PortfolioAlbum::model()->findByPk($albumId);
		
		$photo = PortfolioPhoto::model()->findByPk($_POST['photo']);
		
		if ($entry && $photo && $entry->id == $photo->album_id && $entry->isOwner()) {
			$entry->cover_id = $photo->id;
			$entry->save();
		}
		
		if (Yii::app()->getRequest()->isAjaxRequest) {
			$this->sendJsonSuccess();
		} else {
			$this->redirect(array('portfolioAlbum/' . $entry->id));
		}
	}
}