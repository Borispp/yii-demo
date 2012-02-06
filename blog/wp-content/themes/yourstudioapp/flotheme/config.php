<?php
$flotheme_config = array(
	'labels' => array(//Block for naming everything in admin panel.
		'menu-main'		=> 'Flotheme',
		'menu-options'	=> 'Theme options'
	),
    'fields'    => array(
        'rss_url'   => array(
            'label'         => 'RSS Feed URL',
            'type'          => 'text',
            'default'       => '',
            'description'   => 'If you want to use Feedburner to track your RSS readers, insert your Feed Address here.<br/>Example: http://feeds2.feedburner.com/flosites<br/>Leave it blank if you want to use the standard WordPress Feed.',
        ),
        'copyright'    => array(
            'label'         => 'Copyright',
            'type'          => 'text',
            'default'       => '',
        ),
        'twitter'   => array(
            'label'         => 'Twitter',
            'description'   => 'Enter your full twitter profile url (will not show if empty)',
            'type'          => 'text',
            'default'       => '',
        ),
        'facebook'  => array(
            'label'         => 'Facebook',
            'description'   => 'Enter your full facebook profile url (will not show if empty)',
            'type'          => 'text',
            'default'       => '',
        ),
        
        'twitter_feed'      => array(
            'label'         => 'Display Latest Tweet',
            'type'          => 'checkbox',
            'default'       => 0,
        ),
        
        'tracking_code_enabled'   => array(
            'label'         => 'Enable Tracking Code',
            'type'          => 'checkbox',
            'default'       => 0,
            'toggle'        => 'analytics',
        ),
        'tracking_code'   => array(
            'label'         => 'Tracking Code',
            'description'   => 'Enter your google analytics code here',
            'type'          => 'textarea',
            'default'       => '',
        ),
    ),

    'boxes_toggle'  => array(
        'tracking_code_enabled' => 'analytics'
    ),

    'boxes'    => array(
        'general'   => array(
            'label' => 'General',
            'options'   => array(
                'rss_url', 'copyright', 'tracking_code_enabled', 'twitter_feed',
            ),
        ),
        'links'   => array(
            'label'     => 'Links',
            'options'     => array(
                'twitter', 'facebook',
            ),
        ),
        'analytics'  => array(
            'label'     => 'Tracking Code',
            'options'   => array(
                'tracking_code',
            ),
        ),
    ),
);