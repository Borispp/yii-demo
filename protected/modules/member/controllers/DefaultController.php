<?php
class DefaultController extends YsaMemberController
{
	public function actionIndex()
	{
		$zd = new Zendesk;
		$requests = $zd->requests($this->member()->email);
		
		$this->setMemberPageTitle(Yii::t('title', 'Dashboard'));
		$this->setMetaTitle(Yii::t('title', 'Dashboard'));
		
		$this->render('index', array(
			'zd_requests' => (array) $requests
		));
	}
	
	public function actionError()
	{
        $error=Yii::app()->errorHandler->error;
		
		switch($error['code']) {
			case 403:
				$errCode = '403';
				break;
			case 404:
			default:
				$errCode = '404';
				break;
		}
		
		$page = Page::model()->findBySlug('page-' . $errCode);
		
        if($error) {
            if(Yii::app()->request->isAjaxRequest) {
                $this->sendJsonError(array(
					'msg' => $error['message'],
				));
            } else {
				
				$this->setMemberPageTitle($page->short);
				
                $this->render('error', array(
					'error'		=> $error,
					'page'		=> $page,
					'errCode'	=> $errCode,
				));
            }
        } else {
			$this->redirect(Yii::app()->homeUrl);
		}
	}
}