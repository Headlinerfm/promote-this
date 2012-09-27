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


add_action('post_row_actions', 'testing', 10, 2);
function testing($actions,$post){
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


?>
