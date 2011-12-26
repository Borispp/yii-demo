<?php
class RecoveryController extends YsaFrontController
{
    public $defaultAction = 'recover';
    
    public function actionChangepassword()
    {
        $this->render('changepassword');
    }

    public function actionRecover()
    {
        if (Yii::app()->user->isLoggedIn()) {
            $this->redirect(Yii::app()->user->returnUrl);
        }
        
        $k = isset ($_GET['k']) ? $_GET['k'] : null;
        
        // user returns with key to change password
        if ($k) {
            $user = User::model()->findByAttributes(array('activation_key' => $k));
			
            if ($user) {
                $entry = new ChangePasswordForm();
				
                if(isset($_POST['ChangePasswordForm'])) {
                    $entry->attributes = $_POST['ChangePasswordForm'];
                    if($entry->validate()) {
                        $user->password = YsaHelpers::encrypt($entry->password);
                        $user->generateActivationKey();
                        if ($user->state == User::STATE_INACTIVE) {
                            $user->status = User::STATE_ACTIVE;
                        }
						
                        // save new password and regenerated activation key
                        $user->save();
                        
                        Yii::app()->user->setFlash('recoveryMessage', "New password is saved. Now you can log in with your credentials.");
                        $this->redirect($this->createUrl('/recovery'));
                    }
                } 
                
                $this->render('changepassword', array('entry' => $entry));

            } else { // invalid recovery key
                Yii::app()->user->setFlash('recoveryMessage', "Incorrect recovery link.");
                $this->redirect($this->createUrl('/recovery'));
            }
        } else { // show password recovery form with email
            
            $entry = new RecoveryForm();
            
            if(isset($_POST['RecoveryForm'])) {
                $form->attributes = $_POST['RecoveryForm'];
                
                if($entry->validate()) {
                    // find user
                    $entry = User::model()->findbyPk($entry->user_id);
                    
                    $activation_url = 'http://' . $_SERVER['HTTP_HOST'] . $this->createUrl('/recovery', array("k" => $entry->activation_key));
                    
                    Email::model()->send(
                        array($entry->email, $entry->name()), 
                        'member_recovery', 
                        array(
                            'name'  => $entry->name(),
                            'email' => $entry->email,
                            'link'  => $entry->getRecoveryLink(),
                        )
                    );
                    
                    Yii::app()->user->setFlash('recoveryMessage', "Please check your email. An instructions was sent to your email address.");
                    $this->refresh();
                }
            }
            
            $this->render('recover', array('entry' => $entry));
        }
    }
}