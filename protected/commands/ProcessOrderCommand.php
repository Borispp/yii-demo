<?php
/**
 * Execute in console 
 * $ yiic processorder command --arg_name=arg_val
 */
class ProcessOrderCommand extends CConsoleCommand
{
	
	/**
	 * Process single order
	 * $ yiic processorder single --id=value
	 *
	 * @param integer $id 
	 */
	public function actionSingle( $id )
	{
		if ( !is_numeric($id) )
			Yii::app()->end( "Id must be a numeric value\n" );
		
		$order = UserOrder::model()
					->with( array( 'photos' => array( 'with' => 'photo' ), 'member' ) )
					->findByPk( $id );
		
		if ( null === $order )
			Yii::app()->end( "Inexistent order Id - [{$id}]\n" );
		
		/* try to forward order to smugmug
		try
		{
			$order->member->smugmugSetAccessToken();
			$order->member->smugmug()->albums_create( 'title=TestAlbum' );
		}
		catch ( Exception $e )
		{
				echo (string) $e;
		}
		*/
		
		/* Iterate through photos
		foreach( $order->photos as $order_photo )
		{
			var_dump( $order_photo->size, $order_photo->photo->basename );
		}
		*/
		
		echo $id."\n";
	}
	
}