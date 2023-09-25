<?php
/**
 * Newsletter Signup - Footer.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-11-04
 */

?>

<div class="l-footer__newsletter" style="border-bottom: 0;">
	<?php if ( 0 ): ?>
	<p class="l-footer__newsletter-heading t-bold" style="color: #ccc;"><?php esc_html_e( 'Newsletter Signup', 'pmc-rollingstone' ); ?></p>
	<div class="l-footer__newsletter-form">
		<form class="c-form c-form--footer" action="https://media.us1.list-manage.com/subscribe/post?u=a9d74bfce08ba307bfa8b9c78&amp;id=435b42b91d" method="POST" target="_blank">
			<div class="c-form__group">
				<div class="c-form__field t-semibold">
					<?php
					\PMC::render_template(
						CHILD_THEME_PATH . '/template-parts/module/newsletter-input-email.php', [
							'css_class' => 'c-form__input',
						], true
					);
					?>
				</div><!-- .c-form__field -->
				<button class="c-form__button">
					<span class="t-bold t-bold--upper">
						<?php esc_html_e( 'Submit', 'pmc-rollingstone' ); ?>
					</span>
				</button>
			</div><!-- .c-form__group -->
		</form><!-- .c-form -->
	</div>
	<?php endif; ?>
</div><!-- .l-footer__newsletter -->
