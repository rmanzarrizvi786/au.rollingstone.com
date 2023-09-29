<?php
/**
 * Card - Social Share
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

use PMC\Social_Share_Bar\Config;
use Rolling_Stone\Inc\Sharing;

$sharing_icons = Sharing::get_instance()->get_icons();

if ( Sharing::has_icons( $sharing_icons ) ) :

	foreach ( $sharing_icons['primary'] as $id => $share_icon ) : ?>
		<?php
		if ( Config::CM === $id ) {
			continue;
		}
		?>
		<li class="c-social-bar__item">
		<?php Sharing::build_link( $share_icon, $id ); ?>
	</li>
	<?php endforeach; ?>

<li class="c-social-bar__item" data-collapsible-toggle>
	<a href="#"
		class="c-social-bar__link c-social-bar__link--show-more"
		title="<?php esc_attr_e( 'Show more sharing options', 'pmc-rollingstone' ); ?>">
		<span class="c-icon <?php echo esc_attr( Sharing::get_icon_modifier_class() ); ?>">
			<svg><use xlink:href="#svg-icon-plus"></use></svg>
		</span>
		<span class="screen-reader-text"><?php esc_html_e( 'Show more sharing options', 'pmc-rollingstone' ); ?></span>
	</a>
</li>

<?php foreach ( $sharing_icons['secondary'] as $id => $share_icon ) : ?>
	<?php
	if ( Config::CM === $id ) {
		continue;
	}
	?>
	<li class="c-social-bar__item" data-collapsible-panel>
		<?php Sharing::build_link( $share_icon, $id ); ?>
	</li>
<?php
endforeach;

endif;
