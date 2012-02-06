<?php
class Flotheme_App extends Flotheme
{
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Add scripts, styles, etc. to frontend
     */
    public function initFrontend() {
        wp_deregister_script('jquery');
        wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');
		
		
		wp_enqueue_script('modernizr', YOURSTUDIOAPP_WEBSITE . '/resources/js/plugins/modernizr-2.0.6.js');
		wp_enqueue_script('jquery_tools', 'http://cdn.jquerytools.org/1.2.6/full/jquery.tools.min.js', array('jquery'));
		wp_enqueue_script('ysa_screen', YOURSTUDIOAPP_WEBSITE . '/resources/js/screen.js', array('jquery'));
		wp_enqueue_script('ysa_front', YOURSTUDIOAPP_WEBSITE . '/resources/js/front.js', array('jquery', 'ysa_screen'));

		wp_enqueue_style('front_css', YOURSTUDIOAPP_WEBSITE . '/resources/css/front.css');
    }
    
    /**
     * Initialize menus
     * Leave empty, if no menus used
     */
    public function initMenus() {

    }
    
    /**
     * Init custom post types
     * Use _addPostType wrapper to add new
     * Leave empty if no custom post types used
     */
    public function initPostTypes()
    {
		
    }
    
    /**
     * Init filters
     */
    public function initFilters() {
        $this->_filters->init();
    }
    
    /**
     * Init actions
     */
    public function initActions()
    {
        $this->_actions->init();
    }
    
    /**
     * Init plugins
     * Use _initPlugin wrapper to add new plugin
     */
    public function initPlugins()
    {
        $plugins = array('archives', 'pagination');
        
        foreach ($plugins as $plugin) {
            $this->_initPlugin($plugin);
        }
    }
    
    /**
     * Everything that should be initialized through simple call
     */
    public function initThemeSupport()
    {
        # add theme support
        add_theme_support('post-thumbnails', array('post'));
        
        // add automatic feed links
        add_theme_support( 'automatic-feed-links' );
    }
}