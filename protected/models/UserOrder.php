<?php

/**
 * This is the model class for table "user_order".
 *
 * The followings are the available columns in table 'user_order':
 * @property string $id
 * @property integer $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $address1
 * @property string $address2
 * @property string $city
 * @property string $country
 * @property string $state
 * @property string $zip
 * @property string $phone_day
 * @property string $phone_evening
 * @property string $phone_mobile
 * @property string $fax
 * @property string $email
 * @property string $notes
 * @property date $created
 *
 * Relations
 * @property User $user
 */
class UserOrder extends YsaActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return UserOrder the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('first_name, last_name, city, country, state, zip, phone_day, phone_evening, phone_mobile, fax, email', 'length', 'max'=>50),
			array('address1, address2', 'length', 'max'=>255),
			array('notes, created', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, first_name, last_name, address1, address2, city, country, state, zip, phone_day, phone_evening, phone_mobile, fax, email, notes', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user'		=> array(self::BELONGS_TO, 'User', 'user_id'),
			'member'	=> array(self::BELONGS_TO, 'Member', 'user_id', 'condition' => "role='member'"),
			'photos'	=> array(self::HAS_MANY, 'UserOrderPhoto', 'order_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'address1' => 'Address1',
			'address2' => 'Address2',
			'city' => 'City',
			'country' => 'Country',
			'state' => 'State',
			'zip' => 'Zip',
			'phone_day' => 'Phone Day',
			'phone_evening' => 'Phone Evening',
			'phone_mobile' => 'Phone Mobile',
			'fax' => 'Fax',
			'email' => 'Email',
			'notes' => 'Notes',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('address1',$this->address1,true);
		$criteria->compare('address2',$this->address2,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('zip',$this->zip,true);
		$criteria->compare('phone_day',$this->phone_day,true);
		$criteria->compare('phone_evening',$this->phone_evening,true);
		$criteria->compare('phone_mobile',$this->phone_mobile,true);
		$criteria->compare('fax',$this->fax,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('notes',$this->notes,true);

		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
			));
	}


	public function searchCriteria()
	{
		$criteria = new CDbCriteria();
		$criteria->compare('user_id', Member::model()->findByPk(Yii::app()->user->getId())->id);
		$fields = Yii::app()->session[self::SEARCH_SESSION_NAME];
		if (null === $fields) {
			return $criteria;
		}
		extract($fields);
		// search by keyword
//		if (isset($keyword) && $keyword) {
//			$criteria->compare('message', $keyword, true, 'AND');
//		}

		// search by state
//		if (isset($unread) && $unread != '') {
//			$criteria->compare('unread', $unread);
//		}
		// sort entries
		if (isset($order_by) && isset($order_sort)) {
			if (!in_array($fields['order_by'], array_keys($this->attributes))) {
				$order_by = 'id';
			}

			if (!in_array($order_sort, array('ASC', 'DESC'))) {
				$order_sort = 'DESC';
			}
			$criteria->order = $order_by . ' ' . $order_sort;
		}
		return $criteria;
	}

	public function addPhoto(EventPhoto $obPhoto, $quantity, $size, $style = NULL)
	{
		$obOrderPhoto = new UserOrderPhoto();
		$obOrderPhoto->quantity = $quantity;
		$obOrderPhoto->size = $size;
		$obOrderPhoto->style = $style;
		$obOrderPhoto->order_id = $this->id;
		$obOrderPhoto->photo_id = $obPhoto->id;
		$obOrderPhoto->save();
		return $obOrderPhoto;
	}

	/**
	 * @param string $outputType Destination where to send the document. It can take one of the following values:<ul><li>I: send the file inline to the browser (default). The plug-in is used if available. The name given by name is used when one selects the "Save as" option on the link generating the PDF.</li><li>D: send to the browser and force a file download with the name given by name.</li><li>F: save to a local server file with the name given by name.</li><li>S: return the document as a string (name is ignored).</li><li>FI: equivalent to F + I option</li><li>FD: equivalent to F + D option</li><li>E: return the document as base64 mime multi-part email attachment (RFC 2045)</li></ul>
	 * @return void
	 */
	public function generatePdf($outputType = 'I')
	{
		ob_start();
		?>
	<h1>Order #<?php echo $this->id?></h1>
	<table border="1">
		<tr>
			<th nowrap="nowrap">Client Name</th>
			<td nowrap="nowrap"><?php echo $this->last_name?></td>
		</tr>
		<tr>
			<th  nowrap="nowrap">Client Email</th>
			<td nowrap="nowrap"><?php echo $this->email?></td>
		</tr>
		<tr>
			<th nowrap="nowrap">Order date</th>
			<td nowrap="nowrap"><?php echo $this->created?></td>
		</tr>
	</table>
	<h2>List of ordered photos</h2>
		<table>
			<tr>
				<th nowrap="nowrap">Event Name (ID)</th>
				<th nowrap="nowrap">Album Name (ID)</th>
				<th nowrap="nowrap">Photo name (ID)</th>
				<th nowrap="nowrap">Quantity</th>
				<th nowrap="nowrap">Size</th>
				<th nowrap="nowrap">Style</th>
			</tr>
		<?php
				foreach($this->photos as $obUserOrderPhoto)
	{
		?><tr>
		<th nowrap="nowrap"><?php echo $obUserOrderPhoto->photo->album->event->name?> (<?php echo $obUserOrderPhoto->photo->album->event->id?>)</th>
		<th nowrap="nowrap"><?php echo $obUserOrderPhoto->photo->album->name?> (<?php echo $obUserOrderPhoto->photo->album->id?>)</th>
		<th nowrap="nowrap"><?php echo $obUserOrderPhoto->photo->name?> (<?php echo $obUserOrderPhoto->photo->id?>)</th>
		<th nowrap="nowrap"><?php echo $obUserOrderPhoto->quantity?></th>
		<th nowrap="nowrap"><?php echo $obUserOrderPhoto->style?></th>
		<th nowrap="nowrap"><?php echo $obUserOrderPhoto->size?></th>
	</tr>
	<?php
 		}
		echo '</table>';
		$template = ob_get_clean();
		$pdf = Yii::createComponent('application.extensions.tcpdf.ETcPdf',
			'P', 'cm', 'A4', true, 'UTF-8');
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor("YSA");
		$pdf->SetTitle("Order #".$this->id);
		$pdf->SetSubject("Photo order");
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->writeHTML($template, true);
		return $pdf->Output($this->_getPdfPath(), $outputType);
	}

	/**
	 * @return string
	 */
	public function _getPdfPath()
	{
		return rtrim(Yii::getPathOfAlias('webroot.resources.pdf'), '/').'/order'.$this->id.'.pdf';
	}

	/**
	 * Generates PDF-file and return file path
	 * @return string
	 */
	public function getPdfPath()
	{
		$this->generatePdf('F');
		return $this->_getPdfPath();
	}

	public function getPdfUrl()
	{
		return YsaHelpers::path2Url($this->getPdfPath());
	}
}