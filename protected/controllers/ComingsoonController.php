<?php
class ComingsoonController extends YsaFrontController
{
    public function actionIndex()
    {
		$this->redirect(Yii::app()->homeUrl);
		
        $page = Page::model()->findBySlug('coming-soon');
        $this->setMeta($page->meta());

		$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/fancybox.js', CClientScript::POS_END)
				  ->registerCssFile(Yii::app()->baseUrl . '/resources/css/plugins/fancybox.css');
		
		$newsletterForm = new NewsletterForm();
		
        $this->render('index', array(
            'page' => $page,
			'newsletterForm' => $newsletterForm,
        ));
    }
}