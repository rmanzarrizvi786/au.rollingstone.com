<?php

if (post_password_required($post)) {
	if (is_user_logged_in()) {
		do_action( 'wp_footer','wp_admin_bar_render' );
	}

	get_template_part('template-parts/protected/header');
		echo get_the_password_form();
	get_template_part('template-parts/protected/footer');

	exit;
}

/**
 * Single PMC List Post Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-06-01
 */

use \PMC\Lists\Lists;
use \PMC\Lists\List_Post;

get_header();

global $post;

// If a list item post was originally queried, set up the list it's assigned to.
if ( Lists::LIST_ITEM_POST_TYPE === get_post_type() ) {
	$post = List_Post::get_instance()->get_list();
}

setup_postdata( $post );

get_template_part( 'template-parts/list/single' );

get_footer();
