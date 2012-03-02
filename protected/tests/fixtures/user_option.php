<?php

return array(
	'PASS_API_EMAIL' => array(
		'id' => 1,
		'user_id' => 1,
		'name' => UserOption::PASS_EMAIL,
		'value' => mt_rand().'@example.com'
	),
	'PASS_API_PASWD' => array(
		'id' => 2,
		'user_id' => 1,
		'name' => UserOption::PASS_PASSWORD,
		'value' => 'foo'
	)
);