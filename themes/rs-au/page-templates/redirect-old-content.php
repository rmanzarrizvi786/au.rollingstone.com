<?php
/**
 * Template Name: Redirect Old Content
 */

$old_slug = get_query_var( 'old-slug' );
// echo '<pre>'; print_r( $old_slug ); echo '</pre>';
$args = array(
  'name'        => $old_slug,
  'post_type'   => 'post',
  'post_status' => 'publish',
  'numberposts' => 1
);
$my_posts = get_posts($args);
if( $my_posts ) :
  wp_safe_redirect( home_url( '?p=' . $my_posts[0]->ID ) );
endif;
