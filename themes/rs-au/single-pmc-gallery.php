<?php

/**
 * Single pmc gallery template.
 */

get_header();

if (!post_password_required($post)) :
	$gallery_attachments = new Attachments('gallery_attachments');
	$gallery = $gallery_attachments->get_attachments();
?>

	<div id="pmc-gallery">
		<div id="gallery-container" class="c-gallery">
			<header id="gallery-header" class="c-gallery__header">
				<div class="c-gallery-header">
					<h2 class="c-gallery-header__logo">
						<a class="c-gallery-header__logo-link u-gallery-center" href="/" title="Rolling Stone Australia">
							<span class="u-gallery-screen-reader-text c-gallery-header__site-title">Rolling Stone Australia</span>
							<img class="c-gallery-header__logo-image visible" alt="Rolling Stone Australia" src="<?php echo RS_THEME_URL; ?>/assets/src/images/_dev/RS_LOGO-WHITE.svg" width="231" height="42">
						</a>
					</h2>
					<div style="display: flex; margin-top: .5rem">
						<h1 class="c-gallery-header__title u-gallery-center"><span><?php the_title(); ?></span></h1>
						<div class="c-gallery-header__right u-gallery-center">
							<ul class="c-gallery-social-icons">
								<li class="c-gallery-social-icons__icon"><a target="_blank" rel="noopener noreferrer" class="c-gallery-social-icons__icon-facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_the_permalink()); ?>"><svg class="gallery-icon__facebook gallery-icon" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
											<title>Facebook</title>
											<path d="M1343 12v264h-157q-86 0-116 36t-30 108v189h293l-39 296h-254v759h-306v-759h-255v-296h255v-218q0-186 104-288.5t277-102.5q147 0 228 12z"></path>
										</svg></a></li>
								<li class="c-gallery-social-icons__icon"><a target="_blank" rel="noopener noreferrer" class="c-gallery-social-icons__icon-twitter" href="https://twitter.com/intent/tweet/?text=<?php echo urlencode(get_the_title()); ?>&amp;url=<?php echo urlencode(get_the_permalink()); ?>&amp;via=rollingstoneaus"><svg class="gallery-icon__twitter gallery-icon" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
											<title>Twitter</title>
											<path d="M1684 408q-67 98-162 167 1 14 1 42 0 130-38 259.5t-115.5 248.5-184.5 210.5-258 146-323 54.5q-271 0-496-145 35 4 78 4 225 0 401-138-105-2-188-64.5t-114-159.5q33 5 61 5 43 0 85-11-112-23-185.5-111.5t-73.5-205.5v-4q68 38 146 41-66-44-105-115t-39-154q0-88 44-163 121 149 294.5 238.5t371.5 99.5q-8-38-8-74 0-134 94.5-228.5t228.5-94.5q140 0 236 102 109-21 205-78-37 115-142 178 93-10 186-50z"></path>
										</svg></a></li>
								<!-- <li class="c-gallery-social-icons__icon c-gallery-header__back-to-linked-post"><a class="c-gallery-header__back-link" href="/"><svg class="gallery-icon__close-icon gallery-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 368.58 368.58">
									<g>
										<g>
											<path d="M355.45,364.15c7.2-4,11.23-10.06,12.68-18.94a21,21,0,0,1,.45-2.08v-1.52l-.21-1c-.32-1.56-.63-3-1-4.47-1.34-5.69-5.13-10.36-10.4-15.61l-23.77-23.77c-6.63-6.63-13.48-13.5-20.23-20.23-7.52-7.51-15.18-15.13-22.59-22.5L270,233.7c-13-13-25.72-25.71-38.71-38.71a33.8,33.8,0,0,1-2.24-2.55c-.27-.33-.56-.69-.92-1.11L221,182.82l8-7.74,5.42-5.22c3.81-3.66,7.75-7.45,11.52-11.2,9.24-9.19,18.61-18.57,27.68-27.64q7.95-8,15.9-15.91,8.2-8.2,16.44-16.4c9.86-9.83,20-20,30-30,8.07-8.1,17.44-17.55,26.47-27.07,3.15-3.31,5-7.64,5.72-13.22a15.3,15.3,0,0,1,.45-2.21v-.59c-.41-1.34-.79-2.65-1.16-3.94a44.24,44.24,0,0,0-3.13-8.79A25.31,25.31,0,0,0,342.49,0,23.74,23.74,0,0,0,330.4,3.33c-4.59,2.67-8.63,6.83-12.91,11.24-1.38,1.42-2.81,2.89-4.3,4.36-8.32,8.18-16.71,16.61-24.84,24.76L277.17,54.9,260.33,71.72,241.08,90.94l-6.63,6.63c-9.79,9.78-19.91,19.9-29.82,29.87-2.51,2.53-5,5.21-7.73,8.05-1.36,1.44-2.77,2.93-4.27,4.5l-8.7,9.12-8.62-9.2c-1.56-1.66-3.05-3.27-4.49-4.83-3-3.21-5.77-6.25-8.61-9.12-7.81-7.9-15.82-15.9-23.57-23.64q-4.77-4.77-9.53-9.53l-11.29-11.3q-12.48-12.51-25-25L85.1,48.74C75.83,39.46,66.25,29.87,56.79,20.5L56,19.72c-5.1-5-9.91-9.82-15-14.33A22.7,22.7,0,0,0,26.08.08a23.88,23.88,0,0,0-11.9,3.07C5.21,8.29.72,15.39.05,25.47A19.6,19.6,0,0,0,4.69,39.89c5.72,6.63,11.87,12.83,18.39,19.39l2.63,2.64c10.93,11,22.11,22.18,32.93,33l13.74,13.72L86.9,123.14l35.3,35.27C126,162.21,129.88,166,134,170L140,176l8.82,8.7-9,8.47q-2.4,2.25-4.72,4.39c-3.25,3-6.32,5.86-9.27,8.77-8.44,8.33-17,16.88-25.23,25.14l-7.73,7.72L68.54,263.49,56.8,275.23l-10.71,10.7c-8.46,8.44-17.2,17.17-25.76,25.78l-1.82,1.84C14,318.11,9.68,322.42,5.66,327A23.08,23.08,0,0,0,.15,341.81a24.29,24.29,0,0,0,4.93,15.38c4.32,5.57,9.93,9.1,17.63,11.1.36.09.7.19,1,.29h4.74l2.37-.78a24.9,24.9,0,0,0,5.3-2.08,113.67,113.67,0,0,0,15.19-12.57C62.86,341.87,74.45,330.22,85.67,319q6.26-6.3,12.53-12.58l10-10c10.93-10.94,22.23-22.25,33.31-33.4,7.75-7.8,15.3-15.47,23.3-23.59q5.25-5.34,10.58-10.73l8.38-8.5,8.48,8.39c1.57,1.56,3.1,3.06,4.6,4.54,3.24,3.2,6.3,6.22,9.37,9.29q15.27,15.25,30.51,30.52l13.1,13.1,14.48,14.5c11.56,11.58,23.52,23.55,35.3,35.28,9.73,9.68,18.6,18.36,27.13,26.54,2.66,2.54,6.39,4.3,12.89,6.06l.69.21h4.1l2.82-1a57,57,0,0,0,8.21-3.4"></path>
										</g>
									</g>
								</svg></a></li> -->
							</ul>
						</div>
					</div>
				</div>
			</header>
			<main class="c-gallery__main">
				<div class="c-gallery__thumbnails c-galley-thumbnails">
					<ul class="c-gallery-thumbnails__list">
						<?php
						if (is_array($gallery) && !empty($gallery)) :
							$count = 0;;
							foreach ($gallery as $item) :
						?>
								<li class="c-gallery-thumbnail <?php echo $count == 0 ? 'u-gallery-active2' : ''; ?>">
									<a class="c-gallery-thumbnail__link" href="#">
										<?php echo wp_get_attachment_image($item->id, 'thumbnail'); ?>
									</a>
								</li>
						<?php
							endforeach;
						endif;
						?>
					</ul>
				</div>
				<!-- <div class="c-gallery-thumbnail-counter">
				<p class="c-gallery-thumbnail-counter__title">Thumbnails</p>
				<div class="c-gallery-thumbnail-counter__bottom">
					<div class="c-gallery-thumbnail-counter__count"><span class="c-gallery-thumbnail-counter__current">1</span><span class="c-gallery-thumbnail-counter__divider"> of </span><span class="c-gallery-thumbnail-counter__total">13</span></div><a href="#" class="c-gallery-thumbnail-counter__icon"><svg class="gallery-icon__thumbnails gallery-icon" viewBox="0 0 21 21" xmlns="http://www.w3.org/2000/svg">
							<title>Thumbnails</title>
							<path d="M4.001 0c.493 0 .74.226.74.677v3.324c0 .206-.073.38-.216.524A.712.712 0 0 1 4 4.74H.74c-.164 0-.329-.072-.493-.215A.67.67 0 0 1 0 4V.677C0 .226.246 0 .739 0H4zm7.88 0c.493 0 .739.226.739.677v3.324c0 .206-.072.38-.215.524a.712.712 0 0 1-.524.215H8.557c-.451 0-.677-.246-.677-.739V.677c0-.451.226-.677.677-.677h3.324zm8.557.677v3.324c0 .493-.225.74-.677.74h-3.324a.712.712 0 0 1-.523-.216.712.712 0 0 1-.216-.524V.677c0-.451.246-.677.739-.677h3.324c.452 0 .677.226.677.677zM4.001 7.88c.493 0 .74.226.74.677v3.324c0 .452-.247.677-.74.677H.74c-.493 0-.739-.225-.739-.677V8.557c0-.451.246-.677.739-.677H4zm7.88 0c.493 0 .739.226.739.677v3.324c0 .452-.246.677-.739.677H8.557c-.451 0-.677-.225-.677-.677V8.557c0-.451.226-.677.677-.677h3.324zm7.88 0c.452 0 .677.226.677.677v3.324c0 .452-.225.677-.677.677h-3.324c-.493 0-.739-.225-.739-.677V8.557c0-.451.246-.677.739-.677h3.324zm-15.76 7.818c.206 0 .38.072.524.216a.712.712 0 0 1 .215.523v3.324c0 .452-.246.677-.739.677H.74c-.493 0-.739-.225-.739-.677v-3.324a.67.67 0 0 1 .246-.523c.164-.144.329-.216.493-.216H4zm7.88 0c.205 0 .38.072.524.216a.712.712 0 0 1 .215.523v3.324c0 .452-.246.677-.739.677H8.557c-.451 0-.677-.225-.677-.677v-3.324c0-.493.226-.739.677-.739h3.324zm7.88 0c.452 0 .677.246.677.739v3.324c0 .452-.225.677-.677.677h-3.324c-.493 0-.739-.225-.739-.677v-3.324c0-.205.072-.38.216-.523a.712.712 0 0 1 .523-.216h3.324z"></path>
						</svg></a>
				</div>
			</div> -->
				<div class="c-gallery__slider">
					<div class="c-gallery-slider">
						<div class="slick-slider" dir="ltr">
							<?php
							if (is_array($gallery) && !empty($gallery)) :
								$count = 0;
								foreach ($gallery as $item) :
							?>
									<div class="slick-slide" tabindex="-1" aria-hidden="false" data-name="<?php echo sanitize_title($item->fields->title); ?>">
										<div>
											<figure role="presentation" class="c-gallery-slide c-gallery-slide--loaded c-gallery-slide--loaded">
												<?php echo wp_get_attachment_image($item->id, 'large'); ?>
											</figure>
										</div>
									</div>
							<?php
									$count++;
								endforeach;
							endif; ?>

						</div>
					</div>
				</div>
				<aside class="c-gallery__sidebar">
					<div class="c-gallery-sidebar">
						<?php
						if (is_array($gallery) && !empty($gallery)) :
							$count = 0;
							foreach ($gallery as $item) :
						?>
								<div class="c-gallery-sidebar__top gallery-content <?php echo $count == 0 ? 'active' : ''; ?>" id="gallery-content-<?php echo $count; ?>" data-name="<?php echo sanitize_title($item->fields->title); ?>">
									<h2 class="c-gallery-sidebar__title">
										<?php echo isset($item->fields->title) ? $item->fields->title : ''; ?>
									</h2>
									<span class="c-gallery-sidebar__timestamp entry-date published"><?php echo isset($item->fields->date) ? $item->fields->date : ''; ?></span>
									<div class=" c-gallery-sidebar__caption">
										<?php echo isset($item->fields->content) ? $item->fields->content : ''; ?>
									</div>
									<div class="c-gallery-sidebar__caption">
										<div class="c-gallery-sidebar__image-credit">
											<?php echo isset($item->fields->credit) ? $item->fields->credit : ''; ?>
										</div>
									</div>
								</div>
						<?php
								$count++;
							endforeach;
						endif;
						?>
						<div class="c-gallery-sidebar__bottom">
							<div class="c-gallery-sidebar__advert">
								<div class="admz" id="adm-right-rail-gallery">

								</div>
							</div>
						</div>
					</div>
				</aside>

				<div class="c-gallery__intro-card">
					<div class="c-gallery-intro-card"><a href="#" class="c-gallery-intro-card__close-icon u-gallery-close-icon u-gallery-close-icon--small-black"><span class="u-gallery-screen-reader-text">Close this message</span></a>
						<div class="c-gallery-intro-card__header">
							<div class="c-gallery-intro-card__slide-meta">
								<div class="c-gallery-intro-card__date"><?php echo wp_kses_post(get_the_time('F j, Y', get_the_ID())); ?></div>
							</div>
							<h2 class="c-gallery-intro-card__intro-title" style="line-height: 1.3;"><?php the_title(); ?></h2>
						</div>
						<div class="c-gallery-intro-card__content">
							<span>
								<?php the_content(); ?>
							</span>
						</div>
						<button class="c-gallery-intro-card__button">Start Slideshow</button>
						<ul class="c-gallery-intro-card__social-icons">
							<li class="c-gallery-intro-card__social-icon">
								<a target="_blank" rel="noopener noreferrer" class="u-gallery-social-icon u-gallery-facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_the_permalink()); ?>">
									<svg class="gallery-icon__facebook gallery-icon" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
										<title>Facebook</title>
										<path d="M1343 12v264h-157q-86 0-116 36t-30 108v189h293l-39 296h-254v759h-306v-759h-255v-296h255v-218q0-186 104-288.5t277-102.5q147 0 228 12z"></path>
									</svg>
								</a>
							</li>
							<li class="c-gallery-intro-card__social-icon">
								<a target="_blank" rel="noopener noreferrer" class="u-gallery-social-icon u-gallery-twitter" href="https://twitter.com/intent/tweet/?text=<?php echo urlencode(get_the_title()); ?>&amp;url=<?php echo urlencode(get_the_permalink()); ?>&amp; rollingstoneaus">
									<svg class="gallery-icon__twitter gallery-icon" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
										<title>Twitter</title>
										<path d="M1684 408q-67 98-162 167 1 14 1 42 0 130-38 259.5t-115.5 248.5-184.5 210.5-258 146-323 54.5q-271 0-496-145 35 4 78 4 225 0 401-138-105-2-188-64.5t-114-159.5q33 5 61 5 43 0 85-11-112-23-185.5-111.5t-73.5-205.5v-4q68 38 146 41-66-44-105-115t-39-154q0-88 44-163 121 149 294.5 238.5t371.5 99.5q-8-38-8-74 0-134 94.5-228.5t228.5-94.5q140 0 236 102 109-21 205-78-37 115-142 178 93-10 186-50z"></path>
									</svg>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</main>
		</div>
	</div>

	<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
	<script>
		jQuery(document).ready(function($) {
			var thisUrl = '<?php echo get_the_permalink(); ?>';
			var thisTitle = '<?php echo get_the_title(); ?>';
			$('.slick-slider').slick({
				nextArrow: '<a href="#" class="u-gallery-arrow u-gallery-arrow--next"><span class="u-gallery-screen-reader-text">next</span></a>',
				prevArrow: '<a href="#" class="u-gallery-arrow u-gallery-arrow--prev"><span class="u-gallery-screen-reader-text">prev</span></a>',
				speed: 100,
				fade: true,
				cssEase: 'linear'
			}).on('beforeChange', function(event, slick, currentSlide, nextSlide) {
				/* $('#gallery-content-' + currentSlide).fadeOut(100, function() {
					$('#gallery-content-' + nextSlide).fadeIn();
				}); */
				// var newTitle = thisTitle;
				// document.title = newTitle;
				$('#gallery-content-' + currentSlide).hide();
				$('#gallery-content-' + nextSlide).show();
				// var newUrl = thisUrl + $('#gallery-content-' + nextSlide).data('name') + '/';
				// window.history.pushState(null, null, newUrl);
			});

			$('.c-gallery-intro-card__button, .u-gallery-close-icon').on('click', function() {
				$('.c-gallery__intro-card').remove();
			});
		});
	</script>

<?php
else :
?>
	<div style="display: flex; justify-content: center; align-items: center; text-align: center; margin: 1rem auto;">
		<?php echo get_the_password_form(); ?>
	</div>
<?php
endif;

wp_footer();
