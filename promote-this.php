<?php
/**
 * @package Promote This
 * @version 0.1
 */
/*
Plugin Name: Promote This
Plugin URI: http://headliner.fm
Description: simple plugin for Headliner.fm
Author: Bill Cromie
Version: 0.1
Author URI: http://headliner.fm/
*/

defined('ABSPATH') or die('You\'re not supposed to be here.');

function add_promo_script(){

	wp_enqueue_script('promo', plugins_url('promo.js', __FILE__), array('jquery'), '0.1', true);
}

if ( is_admin() ) {
add_action( 'admin_enqueue_scripts', 'add_promo_script' );
}


add_action('post_row_actions', 'promote_this_row_action', 10, 2);

function promote_this_row_action($actions,$post){
	$link = get_permalink( $post->ID);
	$excerpt = get_the_title($post->ID);
	$str="";
	if(strlen($excerpt)>=5){
		$str=$excerpt . " " . $link;
	}else{
		$str=$link;
	}

	$actions['promote_this'] = '<a href="http:\/\/headliner.fm/exchange/promote_this" target="_blank" class="hl_promote_this_button" data-message="' . urlencode($str) .'">Promote this</a>';
	return $actions;
}


add_action( 'add_meta_boxes', 'promote_this_add_custom_box' );

function promote_this_add_custom_box() {
    add_meta_box(
        'myplugin_sectionid',
        __( 'Promote This', 'myplugin_textdomain' ),
        'promote_this_inner_custom_box',
        'post'
    );
    add_meta_box(
        'myplugin_sectionid',
        __( 'Promote This', 'myplugin_textdomain' ),
        'promote_this_inner_custom_box',
        'page'
    );
}


function promote_this_inner_custom_box( $post ) {

  // Use nonce for verification
  wp_nonce_field( plugin_basename( __FILE__ ), 'promote_this_noncename' );

  // The actual fields for data entry
  echo '<label for="myplugin_new_field">';
       _e("Description for this field", 'myplugin_textdomain' );
  echo '</label> ';
  echo '<input type="text" id="myplugin_new_field" name="myplugin_new_field" value="whatever" size="25" />';
}


?>
