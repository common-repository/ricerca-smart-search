<?php
defined( 'ABSPATH' ) || exit;


//Exclude js inline
function ric_wp_rocket_exclude_inline_js( $pattern ) {
	$pattern[] = 'ric_Data';
	$pattern[] = 'ric_Config';
	$pattern[] = 'ric_DataKey';
	return $pattern;
}
add_filter( 'rocket_excluded_inline_js_content', 'ric_wp_rocket_exclude_inline_js');

//Exclude js deferred
function ric_wp_rocket_exclude_defer_js_uncode( $exclude_defer_js ) {
    $exclude_defer_js[] = rocket_clean_exclude_file( get_template_directory_uri() . '/assets/js/front.js' );
     return $exclude_defer_js;
}
add_filter( 'rocket_exclude_defer_js', 'ric_wp_rocket_exclude_defer_js_uncode' );

//Exclude delay js execution
function ric_wp_rocket_exclude_js( $exclude_defer_js ) {
    $exclude_defer_js[] = rocket_clean_exclude_file( get_template_directory_uri() . '/assets/js/front.js' );
     return $exclude_defer_js;
}
add_filter( 'rocket_exclude_js', 'ric_wp_rocket_exclude_js' );
