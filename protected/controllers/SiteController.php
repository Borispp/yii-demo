<?php
class SiteController extends YsaFrontController
{
    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {	
        $page = Page::model()->findBySlug('homepage');
        $this->setMeta($page->meta());

        $this->render('index', array(
            'page' => $page,
        ));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        $error=Yii::app()->errorHandler->error;
        if($error) {
            if(Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            } else {
                $this->render('error', $error);
            }
        }
    }
}