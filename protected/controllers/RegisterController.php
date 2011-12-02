<?php
class RegisterController extends YsaController
{
    public $defaultAction = 'registration';
    
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            'captcha'=>array(
                'class'=>'CCaptchaAction',
                'backColor'=>0xFFFFFF,
            ),
        );
    }
    
    public function init() {
        parent::init();
        YsaHelpers::date();
        if (Yii::app()->user->isLoggedIn()) {
            $this->redirect(Yii::app()->user->returnUrl);
        }
    }

    public function actionRegistration()
    {
        $model=new RegistrationForm('register');

        // uncomment the following code to enable ajax-based validation
        /*
        if(isset($_POST['ajax']) && $_POST['ajax']==='registration-form-registration-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        */

        if(isset($_POST['RegistrationForm'])) {
            $model->attributes=$_POST['RegistrationForm'];
            
            if($model->validate()) {
                $model->created = YsaHelpers::date();
                $model->state = User::STATE_INACTIVE;
                $model->role = User::ROLE_MEMBER;
                $model->password = YsaHelpers::encrypt($model->password);
                $model->generateActivationKey();
               
                if ($model->save(false)) {
                    Yii::app()->user->setFlash('registration', "Thank you for your registration. Please check your email.");
                    $this->refresh();
                }
                
                return;
            }
        }
        $this->render('registration',array('model'=>$model));
    }
}