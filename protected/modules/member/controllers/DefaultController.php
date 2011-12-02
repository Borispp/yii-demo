<?php
class DefaultController extends YsaMemberController
{
    public function actionIndex()
    {
        $this->render('index');
    }
}