<?php
class AuthController extends YsaController
{
    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        if (Yii::app()->user->isLoggedIn()) {
            $this->redirect(Yii::app()->user->returnUrl);
        }

        $model=new LoginForm;

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if(isset($_POST['LoginForm'])) {
            $model->attributes=$_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login()) {
                if (Yii::app()->user->isAdmin()) {
                    $this->redirect($this->createUrl('//admin', array()));
                } else {
//                        $this->redirect(Yii::app()->user->returnUrl);
                    $this->redirect($this->createUrl('//member', array()));
                }
            }
        }
        // display the login form
        $this->render('login',array('model'=>$model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }
    
    public function actionActivate()
    {
        $k = isset($_GET['k']) ? $_GET['k'] : null;
        
        if (!$k) {
            $this->render('activate', array('title' => "User activation", 'content' => "Incorrect activation URL."));
        }
        
        $user = User::model()->findByAttributes(array('activation_key' => $k));
        
        if ($user && $user->state) {
            $this->render('activate', array('title' => "User activation", 'content' => "You account is already activated."));
        } elseif(isset($user->activation_key) && ($user->activation_key==$k)) {
            
            $user->activate();
            
            $this->render('activate', array('title' => "User activation", 'content' => "Your account was successfully activated."));
        } else {
            $this->render('activate', array('title' => "User activation", 'content' => "Incorrect activation URL."));
        }
    }
}