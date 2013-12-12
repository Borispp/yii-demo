<?php
/**
 * @file
 * ApnsPHP_Message_Exception class definition.
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://code.google.com/p/apns-php/wiki/License
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to aldo.armiento@gmail.com so we can send you a copy immediately.
 *
 * @author (C) 2010 Aldo Armiento (aldo.armiento@gmail.com)
 * @version $Id: Exception.php 50 2010-03-01 21:45:23Z aldo.armiento $
 */

/**
 * Exception class.
 *
 * @ingroup ApnsPHP_Message
 */
class ApnsPHP_Message_Exception extends ApnsPHP_Exception
{
}
/**
 * @file
 * ApnsPHP_Message_Custom class definition.
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://code.google.com/p/apns-php/wiki/License
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to aldo.armiento@gmail.com so we can send you a copy immediately.
 *
 * @author (C) 2010 Aldo Armiento (aldo.armiento@gmail.com)
 * @version $Id: Custom.php 78 2010-12-18 18:52:14Z aldo.armiento $
 */

/**
 * The Push Notification Custom Message.
 *
 * The class represents a custom message to be delivered to an end user device.
 * Please refer to Table 3-2 for more information.
 *
 * @ingroup ApnsPHP_Message
 * @see http://tinyurl.com/ApplePushNotificationPayload
 */
class ApnsPHP_Message_Custom extends ApnsPHP_Message
{
	protected $_sActionLocKey; /**< @type string The "View" button title. */
	protected $_sLocKey; /**< @type string A key to an alert-message string in a Localizable.strings file */
	protected $_aLocArgs; /**< @type array Variable string values to appear in place of the format specifiers in loc-key. */
	protected $_sLaunchImage; /**< @type string The filename of an image file in the application bundle. */

	/**
	 * Set the "View" button title.
	 *
	 * If a string is specified, displays an alert with two buttons.
	 * iOS uses the string as a key to get a localized string in the current localization
	 * to use for the right button’s title instead of "View". If the value is an
	 * empty string, the system displays an alert with a single OK button that simply
	 * dismisses the alert when tapped.
	 *
	 * @param  $sActionLocKey @type string @optional The "View" button title, default
	 *         empty string.
	 */
	public function setActionLocKey($sActionLocKey = '')
	{
		$this->_sActionLocKey = $sActionLocKey;
	}

	/**
	 * Get the "View" button title.
	 *
	 * @return @type string The "View" button title.
	 */
	public function getActionLocKey()
	{
		return $this->_sActionLocKey;
	}

	/**
	 * Set the alert-message string in Localizable.strings file for the current
	 * localization (which is set by the user’s language preference).
	 *
	 * The key string can be formatted with %@ and %n$@ specifiers to take the variables
	 * specified in loc-args.
	 *
	 * @param  $sLocKey @type string The alert-message string.
	 */
	public function setLocKey($sLocKey)
	{
		$this->_sLocKey = $sLocKey;
	}

	/**
	 * Get the alert-message string in Localizable.strings file.
	 *
	 * @return @type string The alert-message string.
	 */
	public function getLocKey()
	{
		return $this->_sLocKey;
	}

	/**
	 * Set the variable string values to appear in place of the format specifiers
	 * in loc-key.
	 *
	 * @param  $aLocArgs @type array The variable string values.
	 */
	public function setLocArgs($aLocArgs)
	{
		$this->_aLocArgs = $aLocArgs;
	}

	/**
	 * Get the variable string values to appear in place of the format specifiers
	 * in loc-key.
	 *
	 * @return @type string The variable string values.
	 */
	public function getLocArgs()
	{
		return $this->_aLocArgs;
	}

	/**
	 * Set the filename of an image file in the application bundle; it may include
	 * the extension or omit it.
	 *
	 * The image is used as the launch image when users tap the action button or
	 * move the action slider. If this property is not specified, the system either
	 * uses the previous snapshot, uses the image identified by the UILaunchImageFile
	 * key in the application’s Info.plist file, or falls back to Default.png.
	 * This property was added in iOS 4.0.
	 *
	 * @param  $sLaunchImage @type string The filename of an image file.
	 */
	public function setLaunchImage($sLaunchImage)
	{
		$this->_sLaunchImage = $sLaunchImage;
	}

	/**
	 * Get the filename of an image file in the application bundle.
	 *
	 * @return @type string The filename of an image file.
	 */
	public function getLaunchImage()
	{
		return $this->_sLaunchImage;
	}

	/**
	 * Get the payload dictionary.
	 *
	 * @return @type array The payload dictionary.
	 */
	protected function _getPayload()
	{
		$aPayload = parent::_getPayload();

		$aPayload['aps']['alert'] = array();

		if (isset($this->_sText) && !isset($this->_sLocKey)) {
			$aPayload['aps']['alert']['body'] = (string)$this->_sText;
		}

		if (isset($this->_sActionLocKey)) {
			$aPayload['aps']['alert']['action-loc-key'] = $this->_sActionLocKey == '' ?
				null : (string)$this->_sActionLocKey;
		}

		if (isset($this->_sLocKey)) {
			$aPayload['aps']['alert']['loc-key'] = (string)$this->_sLocKey;
		}

		if (isset($this->_aLocArgs)) {
			$aPayload['aps']['alert']['loc-args'] = $this->_aLocArgs;
		}

		if (isset($this->_sLaunchImage)) {
			$aPayload['aps']['alert']['launch-image'] = (string)$this->_sLaunchImage;
		}

		return $aPayload;
	}
}

/**
 * @file
 * ApnsPHP_Message class definition.
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://code.google.com/p/apns-php/wiki/License
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to aldo.armiento@gmail.com so we can send you a copy immediately.
 *
 * @author (C) 2010 Aldo Armiento (aldo.armiento@gmail.com)
 * @version $Id: Message.php 100 2011-09-12 13:50:56Z aldo.armiento $
 */

/**
 * @defgroup ApnsPHP_Message Message
 * @ingroup ApplePushNotificationService
 */

/**
 * The Push Notification Message.
 *
 * The class represents a message to be delivered to an end user device.
 * Notification Service.
 *
 * @ingroup ApnsPHP_Message
 * @see http://tinyurl.com/ApplePushNotificationPayload
 */
class ApnsPHP_Message
{
	const PAYLOAD_MAXIMUM_SIZE = 256; /**< @type integer The maximum size allowed for a notification payload. */
	const APPLE_RESERVED_NAMESPACE = 'aps'; /**< @type string The Apple-reserved aps namespace. */

	protected $_bAutoAdjustLongPayload = true; /**< @type boolean If the JSON payload is longer than maximum allowed size, shorts message text. */

	protected $_aDeviceTokens = array(); /**< @type array Recipients device tokens. */

	protected $_sText; /**< @type string Alert message to display to the user. */
	protected $_nBadge; /**< @type integer Number to badge the application icon with. */
	protected $_sSound; /**< @type string Sound to play. */

	protected $_aCustomProperties; /**< @type mixed Custom properties container. */

	protected $_nExpiryValue = 604800; /**< @type integer That message will expire in 604800 seconds (86400 * 7, 7 days) if not successful delivered. */

	protected $_mCustomIdentifier; /**< @type mixed Custom message identifier. */

	/**
	 * Constructor.
	 *
	 * @param  $sDeviceToken @type string @optional Recipients device token.
	 */
	public function __construct($sDeviceToken = null)
	{
		if (isset($sDeviceToken)) {
			$this->addRecipient($sDeviceToken);
		}
	}

	/**
	 * Add a recipient device token.
	 *
	 * @param  $sDeviceToken @type string Recipients device token.
	 * @throws ApnsPHP_Message_Exception if the device token
	 *         is not well formed.
	 */
	public function addRecipient($sDeviceToken)
	{
		if (!preg_match('~^[a-f0-9]{64}$~i', $sDeviceToken)) {
			throw new ApnsPHP_Message_Exception(
				"Invalid device token '{$sDeviceToken}'"
			);
		}
		$this->_aDeviceTokens[] = $sDeviceToken;
	}

	/**
	 * Get a recipient.
	 *
	 * @param  $nRecipient @type integer @optional Recipient number to return.
	 * @throws ApnsPHP_Message_Exception if no recipient number
	 *         exists.
	 * @return @type string The recipient token at index $nRecipient.
	 */
	public function getRecipient($nRecipient = 0)
	{
		if (!isset($this->_aDeviceTokens[$nRecipient])) {
			throw new ApnsPHP_Message_Exception(
				"No recipient at index '{$nRecipient}'"
			);
		}
		return $this->_aDeviceTokens[$nRecipient];
	}

	/**
	 * Get the number of recipients.
	 *
	 * @return @type integer Recipient's number.
	 */
	public function getRecipientsNumber()
	{
		return count($this->_aDeviceTokens);
	}

	/**
	 * Get all recipients.
	 *
	 * @return @type array Array of all recipients device token.
	 */
	public function getRecipients()
	{
		return $this->_aDeviceTokens;
	}

	/**
	 * Set the alert message to display to the user.
	 *
	 * @param  $sText @type string An alert message to display to the user.
	 */
	public function setText($sText)
	{
		$this->_sText = $sText;
	}

	/**
	 * Get the alert message to display to the user.
	 *
	 * @return @type string The alert message to display to the user.
	 */
	public function getText()
	{
		return $this->_sText;
	}

	/**
	 * Set the number to badge the application icon with.
	 *
	 * @param  $nBadge @type integer A number to badge the application icon with.
	 * @throws ApnsPHP_Message_Exception if badge is not an
	 *         integer.
	 */
	public function setBadge($nBadge)
	{
		if (!is_int($nBadge)) {
			throw new ApnsPHP_Message_Exception(
				"Invalid badge number '{$nBadge}'"
			);
		}
		$this->_nBadge = $nBadge;
	}

	/**
	 * Get the number to badge the application icon with.
	 *
	 * @return @type integer The number to badge the application icon with.
	 */
	public function getBadge()
	{
		return $this->_nBadge;
	}

	/**
	 * Set the sound to play.
	 *
	 * @param  $sSound @type string @optional A sound to play ('default sound' is
	 *         the default sound).
	 */
	public function setSound($sSound = 'default')
	{
		$this->_sSound = $sSound;
	}

	/**
	 * Get the sound to play.
	 *
	 * @return @type string The sound to play.
	 */
	public function getSound()
	{
		return $this->_sSound;
	}

	/**
	 * Set a custom property.
	 *
	 * @param  $sName @type string Custom property name.
	 * @param  $mValue @type mixed Custom property value.
	 * @throws ApnsPHP_Message_Exception if custom property name is not outside
	 *         the Apple-reserved 'aps' namespace.
	 */
	public function setCustomProperty($sName, $mValue)
	{
		if ($sName == self::APPLE_RESERVED_NAMESPACE) {
			throw new ApnsPHP_Message_Exception(
				"Property name '" . self::APPLE_RESERVED_NAMESPACE . "' can not be used for custom property."
			);
		}
		$this->_aCustomProperties[trim($sName)] = $mValue;
	}

	/**
	 * Get the first custom property name.
	 *
	 * @deprecated Use getCustomPropertyNames() instead.
	 *
	 * @return @type string The first custom property name.
	 */
	public function getCustomPropertyName()
	{
		if (!is_array($this->_aCustomProperties)) {
			return;
		}
		$aKeys = array_keys($this->_aCustomProperties);
		return $aKeys[0];
	}

	/**
	 * Get the first custom property value.
	 *
	 * @deprecated Use getCustomProperty() instead.
	 *
	 * @return @type mixed The first custom property value.
	 */
	public function getCustomPropertyValue()
	{
		if (!is_array($this->_aCustomProperties)) {
			return;
		}
		$aKeys = array_keys($this->_aCustomProperties);
		return $this->_aCustomProperties[$aKeys[0]];
	}

	/**
	 * Get all custom properties names.
	 *
	 * @return @type array All properties names.
	 */
	public function getCustomPropertyNames()
	{
		if (!is_array($this->_aCustomProperties)) {
			return array();
		}
		return array_keys($this->_aCustomProperties);
	}

	/**
	 * Get the custom property value.
	 *
	 * @param  $sName @type string Custom property name.
	 * @throws ApnsPHP_Message_Exception if no property exists with the specified
	 *         name.
	 * @return @type string The custom property value.
	 */
	public function getCustomProperty($sName)
	{
		if (!array_key_exists($sName, $this->_aCustomProperties)) {
			throw new ApnsPHP_Message_Exception(
				"No property exists with the specified name '{$sName}'."
			);
		}
		return $this->_aCustomProperties[$sName];
	}

	/**
	 * Set the auto-adjust long payload value.
	 *
	 * @param  $bAutoAdjust @type boolean If true a long payload is shorted cutting
	 *         long text value.
	 */
	public function setAutoAdjustLongPayload($bAutoAdjust)
	{
		$this->_bAutoAdjustLongPayload = (boolean)$bAutoAdjust;
	}

	/**
	 * Get the auto-adjust long payload value.
	 *
	 * @return @type boolean The auto-adjust long payload value.
	 */
	public function getAutoAdjustLongPayload()
	{
		return $this->_bAutoAdjustLongPayload;
	}

	/**
	 * PHP Magic Method. When an object is "converted" to a string, JSON-encoded
	 * payload is returned.
	 *
	 * @return @type string JSON-encoded payload.
	 */
	public function __toString()
	{
		try {
			$sJSONPayload = $this->getPayload();
		} catch (ApnsPHP_Message_Exception $e) {
			$sJSONPayload = '';
		}
		return $sJSONPayload;
	}

	/**
	 * Get the payload dictionary.
	 *
	 * @return @type array The payload dictionary.
	 */
	protected function _getPayload()
	{
		$aPayload[self::APPLE_RESERVED_NAMESPACE] = array();

		if (isset($this->_sText)) {
			$aPayload[self::APPLE_RESERVED_NAMESPACE]['alert'] = (string)$this->_sText;
		}
		if (isset($this->_nBadge) && $this->_nBadge > 0) {
			$aPayload[self::APPLE_RESERVED_NAMESPACE]['badge'] = (int)$this->_nBadge;
		}
		if (isset($this->_sSound)) {
			$aPayload[self::APPLE_RESERVED_NAMESPACE]['sound'] = (string)$this->_sSound;
		}

		if (is_array($this->_aCustomProperties)) {
			foreach($this->_aCustomProperties as $sPropertyName => $mPropertyValue) {
				$aPayload[$sPropertyName] = $mPropertyValue;
			}
		}

		return $aPayload;
	}

	/**
	 * Convert the message in a JSON-encoded payload.
	 *
	 * @throws ApnsPHP_Message_Exception if payload is longer than maximum allowed
	 *         size and AutoAdjustLongPayload is disabled.
	 * @return @type string JSON-encoded payload.
	 */
	public function getPayload()
	{
		$sJSONPayload = str_replace(
			'"' . self::APPLE_RESERVED_NAMESPACE . '":[]',
			'"' . self::APPLE_RESERVED_NAMESPACE . '":{}',
			json_encode($this->_getPayload())
		);
		$nJSONPayloadLen = strlen($sJSONPayload);

		if ($nJSONPayloadLen > self::PAYLOAD_MAXIMUM_SIZE) {
			if ($this->_bAutoAdjustLongPayload) {
				$nMaxTextLen = $nTextLen = strlen($this->_sText) - ($nJSONPayloadLen - self::PAYLOAD_MAXIMUM_SIZE);
				if ($nMaxTextLen > 0) {
					while (strlen($this->_sText = mb_substr($this->_sText, 0, --$nTextLen, 'UTF-8')) > $nMaxTextLen);
					return $this->getPayload();
				} else {
					throw new ApnsPHP_Message_Exception(
						"JSON Payload is too long: {$nJSONPayloadLen} bytes. Maximum size is " .
						self::PAYLOAD_MAXIMUM_SIZE . " bytes. The message text can not be auto-adjusted."
					);
				}
			} else {
				throw new ApnsPHP_Message_Exception(
					"JSON Payload is too long: {$nJSONPayloadLen} bytes. Maximum size is " .
					self::PAYLOAD_MAXIMUM_SIZE . " bytes"
				);
			}
		}

		return $sJSONPayload;
	}

	/**
	 * Set the expiry value.
	 *
	 * @param  $nExpiryValue @type integer This message will expire in N seconds
	 *         if not successful delivered.
	 */
	public function setExpiry($nExpiryValue)
	{
		if (!is_int($nExpiryValue)) {
			throw new ApnsPHP_Message_Exception(
				"Invalid seconds number '{$nExpiryValue}'"
			);
		}
		$this->_nExpiryValue = $nExpiryValue;
	}

	/**
	 * Get the expiry value.
	 *
	 * @return @type integer The expire message value (in seconds).
	 */
	public function getExpiry()
	{
		return $this->_nExpiryValue;
	}

	/**
	 * Set the custom message identifier.
	 *
	 * The custom message identifier is useful to associate a push notification
	 * to a DB record or an User entry for example. The custom message identifier
	 * can be retrieved in case of error using the getCustomIdentifier()
	 * method of an entry retrieved by the getErrors() method.
	 * This custom identifier, if present, is also used in all status message by
	 * the ApnsPHP_Push class.
	 *
	 * @param  $mCustomIdentifier @type mixed The custom message identifier.
	 */
	public function setCustomIdentifier($mCustomIdentifier)
	{
		$this->_mCustomIdentifier = $mCustomIdentifier;
	}

	/**
	 * Get the custom message identifier.
	 *
	 * @return @type mixed The custom message identifier.
	 */
	public function getCustomIdentifier()
	{
		return $this->_mCustomIdentifier;
	}
}