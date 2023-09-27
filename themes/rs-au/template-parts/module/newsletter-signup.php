<?php

/**
 * Newsletter Signup - header.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.04.06
 */

?>
<?php if (0) : ?>
	<form class="c-form c-form--faded" action="https://media.us1.list-manage.com/subscribe/post?u=a9d74bfce08ba307bfa8b9c78&amp;id=435b42b91d" method="POST" target="_blank">

		<div class="c-form__group">
			<div class="c-form__field t-semibold">
				<?php
				\PMC::render_template(
					CHILD_THEME_PATH . '/template-parts/module/newsletter-input-email.php',
					[
						'css_class' => 'c-form__input',
					],
					true
				);
				?>
			</div><!-- .c-form__field -->
			<button class="c-form__button">
				<span class="t-bold t-bold--upper">
					<?php esc_html_e('Sign Up', 'pmc-rollingstone'); ?>
				</span>
			</button>
		</div><!-- .c-form__group -->
	</form><!-- .c-form -->
<?php endif; ?>
<a href="https://thebrag.com/observer/" target="_blank"><img src="https://cdn.thebrag.com/observer/The-Brag-Observer_white.png" alt="The Brag Observer" class="img-fluid" width="200" style="max-width: 200px;"></a>