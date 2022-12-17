<?php
/**
 * Subscribe and Newsletter Signup - River.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

?>
<div class="c-subscribe-newsletter-banner">
	<?php if ( 0 ) : // Disabled 2019/11/25 ?>
	<div class="c-subscribe-banner">
		<div class="c-subscribe-banner__wrap">
			<div class="c-subscribe-banner__col">
				<a href="<?php echo esc_url( trailingslashit( home_url( 'subscribe' ) ) ); ?>">
					<?php rollingstone_the_issue_cover( 120, [ 'class' => 'c-subscribe-banner__img' ] ); ?>
				</a>
			</div><!-- .c-subscribe-banner__col -->

			<div class="c-subscribe-banner__col">
				<h2 class="c-subscribe-banner__title t-bold">
					<?php esc_html_e( 'Get The Magazine', 'pmc-rollingstone' ); ?>
				</h2>

				<p class="c-subscribe-banner__paragraph">
					<?php esc_html_e( 'Subscribe today and get a limited-edition tote FREE.', 'pmc-rollingstone' ); ?>
				</p>

				<p>
					<a href="<?php echo esc_url( trailingslashit( home_url( 'subscribe' ) ) ); ?>" class="c-subscribe-banner__button c-form__button">
						<span class="t-bold t-bold--upper">
							<?php esc_html_e( 'Subscribe Now', 'pmc-rollingstone' ); ?>
						</span>
					</a>
				</p>
			</div><!-- .c-subscribe-banner__col -->
		</div><!-- .c-subscribe-banner__wrap -->
	</div><!-- .c-subscribe-banner -->
<?php endif; ?>

	<?php if ( 0 ) : ?>
	<div class="c-newsletter-banner">
		<div class="c-newsletter-banner__wrap">

			<h2 class="c-newsletter-banner__title t-bold">
				<?php esc_html_e( 'Get Our Emails', 'pmc-rollingstone' ); ?>
			</h2>

			<p class="c-newsletter-banner__paragraph">
				<?php esc_html_e( 'Go inside the world of music, culture and entertainment', 'pmc-rollingstone' ); ?>
			</p>

			<form class="c-newsletter-banner__form c-form" action="https://media.us1.list-manage.com/subscribe/post?u=a9d74bfce08ba307bfa8b9c78&amp;id=435b42b91d" method="POST" target="_blank">
				<div class="c-form__group">
					<div class="c-form__field t-semibold">
						<?php
						\PMC::render_template(
							CHILD_THEME_PATH . '/template-parts/module/newsletter-input-email.php',
							[
								'css_class'        => 'c-form__input',
								'placeholder_text' => __( 'Email Address', 'pmc-rollingstone' ),
							],
							true
						);
						?>
					</div><!-- .c-form__field -->
					<button class="c-newsletter-banner__button c-form__button">
						<span class="t-bold t-bold--upper"><?php esc_html_e( 'Sign Up', 'pmc-rollingstone' ); ?></span>
					</button>
				</div><!-- .c-form__group -->
			</form><!-- .c-newsletter-banner__form c-form -->
		</div><!-- .c-newsletter-banner__wrap -->
	</div><!-- .c-newsletter-banner -->
	<?php endif; ?>
</div><!-- .c-subscribe-newsletter-banner -->
