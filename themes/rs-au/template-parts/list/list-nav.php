<?php
/**
 * Lists Nav Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-31
 */

use Rolling_Stone\Inc\List_Nav;
use PMC\Lists\List_Post;

$list_nav = new List_Nav(List_Post::get_instance()->get_list()->ID);
$list_nav = isset($list_nav) ? $list_nav : [];

?>

<nav class="l-header__block l-header__block--list-nav">
	<ul class="l-header__menu l-header__menu--list t-semibold t-semibold--upper" data-list-nav>
		<?php $list_nav->render(); ?>
	</ul><!-- .l-header__menu -->
	<div class="l-header__progress-bar" data-list-progress-bar></div>
</nav>
<!-- .l-header__block--list-nav -->