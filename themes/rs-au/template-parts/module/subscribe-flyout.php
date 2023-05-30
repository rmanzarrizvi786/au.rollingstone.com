<?php
/**
 * Header subscribe flyout template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-13
 */

?>

<div class="c-subscribe">
	<div class="c-subscribe__block c-subscribe__block--get-magazine" style="width: 64%;">
		<div class="c-subscribe__cover">
			<?php
			// rollingstone_the_issue_cover( 184 ); // WPCS: XSS ok.
			rollingstone_next_issue_cover( 184 ); // WPCS: XSS ok.
			?>
		</div>
		<div class="c-subscribe__description">
			<p class="c-subscribe__heading t-bold"><?php esc_html_e( 'Get The Magazine', 'pmc-rollingstone' ); ?></p>
			<p class="c-subscribe__content" style="margin-bottom: 0;"><?php esc_html_e( 'The best in culture from a cultural icon. Subscribe now for more from the authority on music, entertainment, politics and pop culture.', 'pmc-rollingstone' ); ?></p>
			<!-- <p class="c-subscribe__important t-semibold"><?php // esc_html_e( 'Plus, get a limited-edition tote FREE.', 'pmc-rollingstone' ); ?></p> -->
			<a href="<?php echo esc_url( trailingslashit( home_url( 'subscribe-magazine' ) ) ); ?>" class="c-subscribe__button c-subscribe__button--subscribe t-bold t-bold--upper">
				<?php esc_html_e( 'Subscribe Now', 'pmc-rollingstone' ); ?>
			</a>
		</div>
	</div>
	<div class="c-subscribe__block" style="/*width: 100%;*/width: calc(36% - .625rem); padding-left: 1rem; padding-right: 1rem; background-color: #f0ece7;">
		<div class="c-subscribe__description">
			<p class="c-subscribe__heading t-bold" style="color: #000;"><?php esc_html_e( 'Newsletter Signup', 'pmc-rollingstone' ); ?></p>
			<a href="https://thebrag.com/observer/" target="_blank"><img src="<?php echo TBM_CDN; ?>/assets/images/OBSmrec4.jpg"></a>
			<?php if( 0 ): ?>
			<p class="c-subscribe__content"><?php esc_html_e( 'Sign up for our newsletter and go inside the world of music, culture and entertainment.', 'pmc-rollingstone' ); ?></p>
			<form class="c-subscribe__form" action="https://media.us1.list-manage.com/subscribe/post?u=a9d74bfce08ba307bfa8b9c78&amp;id=435b42b91d" method="POST" target="_blank">
				<?php
				\PMC::render_template(
					CHILD_THEME_PATH . '/template-parts/module/newsletter-input-email.php', [
						'css_class' => 'c-subscribe__input',
					], true
				);
				?>
				<button type="submit" class="c-subscribe__button c-subscribe__button--newsletter t-bold t-bold--upper">
					<?php esc_html_e( 'Sign Up', 'pmc-rollingstone' ); ?>
				</button>
			</form>
			<?php endif; ?>
		</div>
	</div>
</div>
