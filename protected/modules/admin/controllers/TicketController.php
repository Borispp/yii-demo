<?php
class TicketController extends YsaAdminController
{
	public function actionView($id)
	{
		$entry = Ticket::model()->findByPk($id);

		if (!$entry) {
			$this->redirect(array('application/'));
		}
		
		$reply = new TicketReply();
		
		if (isset($_POST['TicketReply'])) {
			$reply->attributes = $_POST['TicketReply'];
			
			$reply->ticket_id = $entry->id;
			$reply->reply_by = Yii::app()->user->id;
			if ($reply->validate()) {
				$reply->save();
				
				if (isset($_POST['close']) && $_POST['close']) {
					$entry->state = Ticket::STATE_CLOSED;
					$entry->save();
				}
				if ($reply->notify) {
					$entry->user->notifyByObject($reply);
				}
				
				$this->setSuccessFlash('New Reply has been successfully added.');
				$this->refresh();
			}
		}
		
		$this->render('view', array(
			'entry' => $entry,
			'reply' => $reply,
		));
	}
	
	public function actionToggle($id)
	{
		$entry = Ticket::model()->findByPk($id);

		if (!$entry) {
			$this->redirect(array('application/'));
		}
		
		$entry->state = -$entry->state;
		
		$entry->save();
		
		if (Yii::app()->request->isAjaxRequest) {
			$this->sendJsonSuccess();
		} else {
			$this->redirect(array('ticket/view/id/' . $entry->id . '/'));
		}
	}
}