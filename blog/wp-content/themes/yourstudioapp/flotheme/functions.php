<?php
define('YOURSTUDIOAPP_WEBSITE', 'http://yourstudio.dev');

if ( ! isset( $content_width ) )
	$content_width = 655;

add_filter('the_content_more_link', 'ysa_wrap_more'); 

function ysa_wrap_more($link)
{
	return '<div class="more cf">' . $link . '</div>';
}


function ysa_widgets_init() {
	register_sidebar( array(
		'name' => 'Main Sidebar',
		'id' => 'sidebar-main',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => "</div>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'ysa_widgets_init' );


add_filter( 'the_password_form', 'custom_password_form' );
function custom_password_form() {
	global $post;
	$label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
	$o = '<form class="protected-post-form" action="' . get_option('siteurl') . '/wp-pass.php" method="post">
	' . __( "This post is password protected. To view it please enter your password below:" ) . '
	<input name="post_password" placeholder="Password" id="' . $label . '" type="password" size="20" /><input type="submit" class="blue" name="Submit" value="' . esc_attr__( "Submit" ) . '" />
	</form>
	';
	return $o;
}