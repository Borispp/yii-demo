<?php
class AnnouncementController extends YsaAdminController
{
	public function actionAdd($id)
	{
		$member = Member::model()->findByPk($id);
		$this->setContentTitle('Send annotation to '.$member->name());
		$announcement = new Announcement();
		if(Yii::app()->request->isPostRequest)
		{
			$announcement->attributes=@$_POST['Announcement'];
			if ($announcement->validate())
			{
				$announcement->save();
				$announcement->notifyMember($member);
				$this->redirect(array('member/view/id/'.$id));
			}
		}
		$this->render('add', array(
			'announcement'  => $announcement,
		));
	}

	public function actionAddToAll()
	{
		$this->setContentTitle('Send annotation to all members');
		$announcement = new Announcement();
		if(Yii::app()->request->isPostRequest)
		{
			$announcement->attributes=@$_POST['Announcement'];
			if ($announcement->validate())
			{
				$announcement->save();
				foreach(Member::model()->findAllByAttributes(array('state' => 1,'role' => 'member')) as $member)
				{
					$announcement->notifyMember($member);
				}
				$this->redirect(array('member/'));
			}
		}
		$this->render('add', array(
				'announcement'  => $announcement,
			));
	}
}