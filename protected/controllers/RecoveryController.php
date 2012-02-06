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

			$page = Page::model()->findBySlug('change-password');
			
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

                        Yii::app()->user->setFlash('recoveryMessage', $page->short);
                        $this->redirect(array('login/'));
                    }
                }

				$this->setFrontPageTitle($page->title);
				
                $this->render('changepassword', array('entry' => $entry, 'page' => $page));

            } else { // invalid recovery key
                Yii::app()->user->setFlash('recoveryMessage', "Incorrect recovery link.");
                $this->redirect($this->createUrl('/recovery'));
            }
        } else { // show password recovery form with email

            $form = new RecoveryForm();
			
			$page = Page::model()->findBySlug('restore');

            if(isset($_POST['RecoveryForm'])) {

                $form->attributes = $_POST['RecoveryForm'];

                if($form->validate()) {
                    // find user
                    $entry = User::model()->findbyPk($form->user_id);

                    Email::model()->send(
                        array($entry->email, $entry->name()),
                        'member_recovery',
                        array(
                            'name'  => $entry->name(),
                            'email' => $form->email,
                            'link'  => $entry->getRecoveryLink(),
                        )
                    );

                    Yii::app()->user->setFlash('recoveryMessage', $page->short);
                    $this->refresh();
                }
            }
			
			$this->setFrontPageTitle($page->title);
			
            $this->render('recover', array('entry' => $form, 'page' => $page));
        }
    }
}