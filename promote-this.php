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

//adding the promo.js script only the the admin interface
function add_promo_script(){
	wp_enqueue_script('promo', plugins_url('promo.js', __FILE__), array('jquery'), '0.1', true);
}
if ( is_admin() ) {
add_action( 'admin_enqueue_scripts', 'add_promo_script' );
}


// a wee little function that helps construct our default promo message
function get_promo_str($post){
  $link   = get_permalink($post->ID);
  $title  = get_the_title($post->ID);
  $str    = "";
  if(strlen($title)>=5){
    $str=$title . " " . $link;
  }else{
    $str=$link;
  }
  return $str;
}

// adding a row action to the posts columnar view
add_action('post_row_actions', 'promote_this_row_action', 10, 2);
function promote_this_row_action($actions,$post){
  if ($post->post_status=="publish") {
    $str=get_promo_str($post);
	  $actions['promote_this'] = '<a href="http://headliner.fm/exchange/promote_this" target="_blank" class="hl_promote_this_button" data-message="' . urlencode($str) .'">Promote this</a>';
  }
	return $actions;
}

// adding a row action to the pages columnar view
add_action('page_row_actions', 'promote_this_row_action', 10, 2);


// adding the metabox
add_action( 'add_meta_boxes', 'promote_this_add_custom_box',10 ,2 );
function promote_this_add_custom_box($post_ID,$post) {
    if ($post->post_status=="publish") {
      add_meta_box(
        'myplugin_sectionid',
        __( 'Promote This', 'myplugin_textdomain' ),
        'promote_this_inner_custom_box',
        'post',
        'side',
        'high'
      );
      add_meta_box(
        'myplugin_sectionid',
        __( 'Promote This', 'myplugin_textdomain' ),
        'promote_this_inner_custom_box',
        'page',
        'side',
        'high'
      );
    }
}

//the text for the custom metabox
function promote_this_inner_custom_box( $post ) {
  // Use nonce for verification
  //wp_nonce_field( plugin_basename( __FILE__ ), 'promote_this_noncename' );
  $str=get_promo_str($post);
  // The actual fields for data entry
  echo 'Get recommended to a new audience on Facebook and Twitter, free.
  <a href="http://headliner.fm/exchange/promote_this" target="_blank" class="hl_promote_this_button button-primary" data-message="' . urlencode($str) .'">Promote This</a>';
}


// adding a message to the admin notice section for a published post.
function codex_promo_post_updated_messages( $messages ) {
  global $post, $post_ID;
  $promo_str = get_promo_str($post);
  $messages['post'][6] = sprintf( __('Post published. <a href="%1$s">View post</a> | <a href="http://headliner.fm/exchange/promote_this" target="_blank" class="hl_promote_this_button" data-message="$2%s">Promote This post</a>'), esc_url( get_permalink($post_ID) ),esc_url($promo_str) );
  return $messages;
}
add_filter( 'post_updated_messages', 'codex_promo_post_updated_messages' );

?>
