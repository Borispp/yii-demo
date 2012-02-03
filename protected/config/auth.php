<?php
return array(
	'guest' => array(
		'type' => CAuthItem::TYPE_ROLE,
		'description' => 'Guest',
		'bizRule' => null,
		'data' => null
	),
		'member' => array(
			'type' => CAuthItem::TYPE_ROLE,
			'description' => 'New Member',
			'children' => array(
				'guest',
			),
			'bizRule' => null,
			'data' => null
		),
		'customer' => array(
			'type' => CAuthItem::TYPE_ROLE,
			'description' => 'Member with active subscription',
			'children' => array(
				'guest',
			),
			'bizRule' => null,
			'data' => null
		),
		'expired_customer' => array(
			'type' => CAuthItem::TYPE_ROLE,
			'description' => 'Member with expired subscription',
			'children' => array(
				'guest',
			),
			'bizRule' => null,
			'data' => null
		),
		'admin' => array(
			'type' => CAuthItem::TYPE_ROLE,
			'description' => 'Admin',
			'children' => array(
				'guest',
			),
			'bizRule' => null,
			'data' => null
		),
);