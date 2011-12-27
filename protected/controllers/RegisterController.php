<?php
class RegisterController extends YsaFrontController
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
                $model->state = User::STATE_INACTIVE;
                $model->role = User::ROLE_MEMBER;
                $model->encryptPassword();
                $model->generateActivationKey();

                if ($model->save(false)) {
                    
                    // send confirmation email
                    Email::model()->send(
                        array($model->email, $model->name()), 
                        'member_confirmation', 
                        array(
                            'name'  => $model->name(),
                            'email' => $model->email,
                            'link'  => $model->getActivationLink(),
                        )
                    );
					
					// create new Studio
					$studio = new Studio();
					$studio->user_id = $model->id;
					$studio->save();
					
					// create new Portfolio
					$portfolio = new Portfolio();
					$portfolio->studio_id = $studio->id;
					$portfolio->name = Portfolio::DEFAULT_NAME;
					$portfolio->save();
					
                    Yii::app()->user->setFlash('registration', "Thank you for your registration. Please check your email.");
                    $this->refresh();
                }
                
                return;
            }
        }
        $this->render('registration',array('model' => $model));
    }
}