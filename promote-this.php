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


function get_promo_str($post){
  $link = get_permalink( $post->ID);
  $title = get_the_title($post->ID);
  $str="";
  if(strlen($title)>=5){
    $str=$title . " " . $link;
  }else{
    $str=$link;
  }
  return $str;
}

function promote_this_row_action($actions,$post){
  $str=get_promo_str($post);
	$actions['promote_this'] = '<a href="http:\/\/headliner.fm/exchange/promote_this" target="_blank" class="hl_promote_this_button" data-message="' . urlencode($str) .'">Promote this</a>';
	return $actions;
}


add_action( 'add_meta_boxes', 'promote_this_add_custom_box' );

function promote_this_add_custom_box() {
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


function promote_this_inner_custom_box( $post ) {
  // Use nonce for verification
  //wp_nonce_field( plugin_basename( __FILE__ ), 'promote_this_noncename' );
  $str=get_promo_str($post);
  // The actual fields for data entry
  echo '<a href="http:\/\/headliner.fm/exchange/promote_this" target="_blank" class="hl_promote_this_button" data-message="' . urlencode($str) .'">Promote This</a>';
}

// add_action('pending_to_publish',  'load_promote_notice_on_publish',10, 2);
// add_action('new_to_publish',      'load_promote_notice_on_publish', 10, 2);
// add_action('draft_to_publish',    'load_promote_notice_on_publish', 10, 2);
// add_action('pending_to_publish',  'load_promote_notice_on_publish', 10, 2);
// add_action('future_to_publish',   'load_promote_notice_on_publish', 10, 2);

// function load_promote_notice_on_publish($postID,$post){
//   add_action('admin_notices', 'display_promote_notice',10, 2);
// }

function codex_promo_post_updated_messages( $messages ) {
  global $post, $post_ID;

  $promo_str = get_promo_str($post);

  $messages['post'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Post updated. <a href="%s">View Post</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('Post updated.'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Post restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Post published. <a href="%1$s">View post</a> | <a href="http:\/\/headliner.fm/exchange/promote_this" target="_blank" class="hl_promote_this_button" data-message="$2%s">Promote This</a>'), esc_url( get_permalink($post_ID) ),esc_url($promo_str) ),
    7 => __('Post saved.'),
    8 => sprintf( __('Post submitted. <a target="_blank" href="%s">Preview post</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Post scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview post</a>'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Post draft updated. <a target="_blank" href="%s">Preview post</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}
add_filter( 'post_updated_messages', 'codex_promo_post_updated_messages' );

?>
