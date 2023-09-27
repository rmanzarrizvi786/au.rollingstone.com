<?php

/**
 * Template Name: (Test) Braze
 */

get_header();

add_action('wp_footer', function () {
	global $wpdb;
?>
	<style>
		.c-card--brand .c-card__image,
		.c-card--coverage .c-card__image,
		.c-card--featured-home .c-card__image,
		.c-card--grid .c-card__image {
			transition: opacity .24s cubic-bezier(.455, .03, .515, .955)
		}

		.c-card--brand .c-card__image:focus,
		.c-card--brand .c-card__image:hover,
		.c-card--coverage .c-card__image:focus,
		.c-card--coverage .c-card__image:hover,
		.c-card--featured-home .c-card__image:focus,
		.c-card--featured-home .c-card__image:hover,
		.c-card--grid .c-card__image:focus,
		.c-card--grid .c-card__image:hover {
			opacity: .8
		}

		.c-card--brand .c-card__heading,
		.c-card--featured-home .c-card__heading,
		.c-card--grid .c-card__heading,
		.c-reviews-card__headline,
		.c-the-list {
			transition: color .24s cubic-bezier(.455, .03, .515, .955)
		}

		.c-card--brand .c-card__heading:focus,
		.c-card--brand .c-card__heading:hover,
		.c-card--featured-home .c-card__heading:focus,
		.c-card--featured-home .c-card__heading:hover,
		.c-card--grid .c-card__heading:focus,
		.c-card--grid .c-card__heading:hover,
		.c-reviews-card__headline:focus,
		.c-reviews-card__headline:hover,
		.c-the-list:focus,
		.c-the-list:hover {
			color: dimgray
		}

		.c-cards-grid {
			display: -webkit-box;
			display: -ms-flexbox;
			display: flex;
			flex-wrap: wrap;
			width: calc(100% + 2.5rem);
			margin: 0 -1.25rem;
			min-height: 400px;
		}

		.c-card--grid .c-card__heading {
			margin-top: .625rem;
			font-size: 1.0625rem;
			line-height: 1.4375rem
		}


		@media (max-width:47.9375rem) {
			.c-cards-grid {
				padding: 0 .625rem
			}
		}

		.c-cards-grid__item {
			width: 33.33333%;
			padding: 0 1.25rem 1.25rem;
			margin-bottom: 2.5rem
		}

		@media (max-width:47.9375rem) {
			.c-cards-grid__item {
				margin-bottom: .9375rem;
				padding: 0 .625rem 1.25rem
			}

			.c-cards-grid__item:first-of-type {
				width: 100%
			}

			.c-cards-grid__item:last-of-type {
				display: none
			}
		}

		@media (min-width:48rem) and (max-width:59.9375rem) {
			.c-cards-grid__item:not(:nth-of-type(odd)) {
				border-left: 1px solid #dddee4
			}
		}

		@media (min-width:78.75rem) {

			/* .c-cards-grid__item{width:33.33333%} */
			.c-cards-grid__item:not(:nth-of-type(3n+1)) {
				border-left: 1px solid #dddee4
			}
		}

		.c-crop {
			position: relative;
			overflow: hidden;
			width: 100%;
			margin: 0;
			padding: 0;
			background-color: rgba(0, 0, 0, .05)
		}

		.c-crop:after {
			content: "";
			display: block;
			width: 100%;
			padding-bottom: 75%
		}

		.c-crop__img {
			width: 100%;
			height: 100%;
			object-fit: cover;
			position: absolute;
			object-position: 0 top;
			top: 50%;
			left: 50%;
			width: calc(100% + .125rem);
			max-width: calc(100% + .125rem);
			height: calc(100% + .125rem);
			transform: translate(-50%, -50%)
		}

		.c-card--grid .c-card__tag {
			margin-top: .9375rem;
			margin-bottom: 0
		}

		.c-card--grid .c-card__lead {
			margin-top: .625rem;
			font-size: .875rem;
			letter-spacing: .47px;
			line-height: 1.4375rem
		}

		.c-card--grid .c-card__byline {
			position: absolute;
			bottom: .3125rem;
			font-size: .75rem;
			font-style: italic
		}

		.c-crop--ratio-2x3:after {
			padding-bottom: 150%
		}

		.c-crop--ratio-11x14:after {
			padding-bottom: 127.27273%
		}

		.c-crop--ratio-5x6:after {
			padding-bottom: 120%
		}

		.c-crop--ratio-1x1:after {
			padding-bottom: 100%
		}

		.c-crop--ratio-4x3:after {
			padding-bottom: 75%
		}

		.c-crop--ratio-3x2:after {
			padding-bottom: 66.66667%
		}

		.c-crop--ratio-7x4:after {
			padding-bottom: 57.14286%
		}

		.c-crop--ratio-video:after {
			padding-bottom: 52.19632%;
		}

		.c-crop--ratio-2x1:after {
			padding-bottom: 50%
		}
	</style>
	<script>
		var user_external_id = null;
		<?php
		if (is_user_logged_in()) {
			$user_id = get_current_user_id();
			if (!$user_id)
				return;
			$auth0_user_id = get_user_meta($user_id, $wpdb->prefix . 'auth0_id', true);
		?>
			user_external_id = '<?php echo $auth0_user_id; ?>';
		<?php
		} // If user is logged in
		?>

		window.callBraze = () => {
			+ function(a, p, P, b, y) {
				a.braze = {};
				a.brazeQueue = [];
				for (var s = "BrazeSdkMetadata DeviceProperties Card Card.prototype.dismissCard Card.prototype.removeAllSubscriptions Card.prototype.removeSubscription Card.prototype.subscribeToClickedEvent Card.prototype.subscribeToDismissedEvent Card.fromContentCardsJson Banner CaptionedImage ClassicCard ControlCard ContentCards ContentCards.prototype.getUnviewedCardCount Feed Feed.prototype.getUnreadCardCount ControlMessage InAppMessage InAppMessage.SlideFrom InAppMessage.ClickAction InAppMessage.DismissType InAppMessage.OpenTarget InAppMessage.ImageStyle InAppMessage.Orientation InAppMessage.TextAlignment InAppMessage.CropType InAppMessage.prototype.closeMessage InAppMessage.prototype.removeAllSubscriptions InAppMessage.prototype.removeSubscription InAppMessage.prototype.subscribeToClickedEvent InAppMessage.prototype.subscribeToDismissedEvent InAppMessage.fromJson FullScreenMessage ModalMessage HtmlMessage SlideUpMessage User User.Genders User.NotificationSubscriptionTypes User.prototype.addAlias User.prototype.addToCustomAttributeArray User.prototype.addToSubscriptionGroup User.prototype.getUserId User.prototype.incrementCustomUserAttribute User.prototype.removeFromCustomAttributeArray User.prototype.removeFromSubscriptionGroup User.prototype.setCountry User.prototype.setCustomLocationAttribute User.prototype.setCustomUserAttribute User.prototype.setDateOfBirth User.prototype.setEmail User.prototype.setEmailNotificationSubscriptionType User.prototype.setFirstName User.prototype.setGender User.prototype.setHomeCity User.prototype.setLanguage User.prototype.setLastKnownLocation User.prototype.setLastName User.prototype.setPhoneNumber User.prototype.setPushNotificationSubscriptionType InAppMessageButton InAppMessageButton.prototype.removeAllSubscriptions InAppMessageButton.prototype.removeSubscription InAppMessageButton.prototype.subscribeToClickedEvent automaticallyShowInAppMessages destroyFeed hideContentCards showContentCards showFeed showInAppMessage toggleContentCards toggleFeed changeUser destroy getDeviceId initialize isPushBlocked isPushPermissionGranted isPushSupported logCardClick logCardDismissal logCardImpressions logContentCardsDisplayed logCustomEvent logFeedDisplayed logInAppMessageButtonClick logInAppMessageClick logInAppMessageHtmlClick logInAppMessageImpression logPurchase openSession requestPushPermission removeAllSubscriptions removeSubscription requestContentCardsRefresh requestFeedRefresh requestImmediateDataFlush enableSDK isDisabled setLogger setSdkAuthenticationSignature addSdkMetadata disableSDK subscribeToContentCardsUpdates subscribeToFeedUpdates subscribeToInAppMessage subscribeToSdkAuthenticationFailures toggleLogging unregisterPush wipeData handleBrazeAction".split(" "), i = 0; i < s.length; i++) {
					for (var m = s[i], k = a.braze, l = m.split("."), j = 0; j < l.length - 1; j++) k = k[l[j]];
					k[l[j]] = (new Function("return function " + m.replace(/\./g, "_") + "(){window.brazeQueue.push(arguments); return true}"))()
				}
				window.braze.getCachedContentCards = function() {
					return new window.braze.ContentCards
				};
				window.braze.getCachedFeed = function() {
					return new window.braze.Feed
				};
				window.braze.getUser = function() {
					return new window.braze.User
				};
				(y = p.createElement(P)).type = 'text/javascript';
				y.src = 'https://js.appboycdn.com/web-sdk/4.0/braze.min.js';
				y.async = 1;
				(b = p.getElementsByTagName(P)[0]).parentNode.insertBefore(y, b)
			}(window, document, 'script');

			// initialize the SDK
			braze.initialize('08d1f29b-c48e-4bb3-aef3-e133789a0c89', {
				baseUrl: "sdk.iad-05.braze.com"
			});

			if (user_external_id !== null && user_external_id !== undefined) {
				braze.changeUser(user_external_id)
			}

			window.braze.requestContentCardsRefresh(() => {
				const feed = window.braze.getCachedContentCards()
				const cards = feed.cards
				const ele = jQuery('#feed')

				console.log(cards)

				let html = cards.slice(0, 3).map(card => {
					return `
					<div class="c-cards-grid__item">					
						<article class="c-card c-card--grid c-card--grid--primary ">
							<a href="${ card.url }" class="c-card__wrap" target="_blank">
								<figure class="c-card__image">
									<div class="c-crop c-crop--ratio-video">
										<img width="900" height="600" src="${ card.imageUrl }?resize=900,600&amp;w=160" data-src="${ card.imageUrl }?resize=900,600&amp;w=160" class="c-crop__img wp-post-image visible" alt="" loading="lazy" data-srcset="${ card.imageUrl }?resize=900,600&amp;w=160 160w, ${ card.imageUrl }?resize=900,600&amp;w=285 285w, ${ card.imageUrl }?resize=900,600&amp;w=335 335w, ${ card.imageUrl }?resize=900,600&amp;w=730 730w" sizes="(max-width: 767px) 730px, (max-width: 380px) 345px, 285px" srcset="${ card.imageUrl }?resize=900,600&amp;w=160 160w, ${ card.imageUrl }?resize=900,600&amp;w=285 285w, ${ card.imageUrl }?resize=900,600&amp;w=335 335w, ${ card.imageUrl }?resize=900,600&amp;w=730 730w">
									</div><!-- .c-crop -->
								</figure><!-- .c-card__image -->
								<header class="c-card__header">
									<h3 class="c-card__heading t-bold">
										${ card.title }
									</h3><!-- .c-card__heading -->
									<div class="c-card__tag t-bold t-bold--upper">
										<span class="screen-reader-text">Posted in:</span>
										<span class="c-card__featured-tag">
											${ card.extras?.category }
										</span>
									</div><!-- c-card__tag -->
									<p class="c-card__lead">
										${ card.description }
									</p><!-- c-card__lead -->
								</header><!-- .c-card__header -->
							</a><!-- .c-card__wrap -->
						</article><!-- .c-card--grid -->
					</div><!-- /.c-cards-grid__item -->
					`
				})

				ele.html(html)
			})
		}
	</script>
<?php
});

?>
<div class="l-page__content">
	<div class="l-blog">
	<main class="l-blog__primary">
				
				<div>
						<article>
				<div hidden="" class="cats" data-category="music music-news " data-tags="luke-combs "></div>

		
<header class="l-article-header">

	<div class="l-article-header__block l-article-header__block--breadcrumbs t-semibold t-semibold--upper">
		
<span class="c-breadcrumbs">
	<a href="https://au.rollingstone.com/" class="c-breadcrumbs__link">Home</a>
		<a href="https://au.rollingstone.com/music/" class="c-breadcrumbs__link">Music</a>
		<a href="https://au.rollingstone.com/music/music-news/" class="c-breadcrumbs__link">Music News</a>
	</span><!-- .c-breadcrumbs -->
	</div><!-- .l-article-header__block--breadcrumbs -->

	<time class="l-article-header__block l-article-header__block--time t-semibold t-semibold--upper" datetime="2022-09-08T23:16:06+00:00" itemprop="datePublished" data-pubdate="Sep 09, 2022">
		September 9, 2022 9:16AM	</time><!-- .l-article-header__block--time -->

	<div class="l-article-header__siteserved-ad">  </div>

	
<div class="c-badge c-badge--sponsored">
	</div><!-- .c-badge--sponsored -->

		<h1 class="l-article-header__row l-article-header__row--title t-bold t-bold--condensed" data-title="Luke Combs Is Coming to Australia and New Zealand on His World Tour" data-share-title="Luke+Combs+Is+Coming+to+Australia+and+New+Zealand+on+His+World+Tour" data-share-url="https%3A%2F%2Fau.rollingstone.com%2Fmusic%2Fmusic-news%2Fluke-combs-australia-new-zealand-tour-42767%2F" data-article-number="1">
		Luke Combs Is Coming to Australia and New Zealand on His World Tour	</h1><!-- .l-article-header__row--title -->

			<h2 class="l-article-header__row l-article-header__row--lead t-semibold t-semibold--condensed">
			<p>The US country music superstar has announced an Australia and New Zealand leg of his huge world tour.</p>
		</h2><!-- .l-article-header__row--lead -->
	
	
	<div class="l-article-header__block l-article-header__block--byline">
		
<div class="c-byline">
	<div class="c-byline__authors">
		<em class="c-byline__by">By</em>

						<div class="c-byline__author">
											<a href="https://au.rollingstone.com/author/lochriec/" class="c-byline__link t-bold t-bold--upper author" rel="author" data-author="Conor Lochrie">
							Conor Lochrie							<svg class="c-byline__icon" width="14" height="14">
								<use href="#svg-icon-more"></use>
							</svg>
						</a>
						<div class="c-byline__detail">
							
<div class="c-author">
	
	<div class="c-author__meta">
		<h4 class="c-author__heading t-bold">
			<a href="https://au.rollingstone.com/author/lochriec/" rel="author" class="c-author__meta-link">
				Conor Lochrie			</a>
		</h4>

		
			</div>

	<h4 class="c-author__heading t-bold">Conor Lochrie's Most Recent Stories</h4>

		<ul class="c-author__posts t-semibold">
				<li class="c-author__post">
			<a href="https://au.rollingstone.com/music/music-features/billie-eilish-concert-review-42770/" class="c-author__meta-link c-author__meta-link--post">
				Review: An Emotional Billie Eilish Put on A Modern Pop Spectacle in Auckland			</a>
		</li>
				<li class="c-author__post">
			<a href="https://au.rollingstone.com/music/music-news/luke-combs-australia-new-zealand-tour-42767/" class="c-author__meta-link c-author__meta-link--post">
				Luke Combs Is Coming to Australia and New Zealand on His World Tour			</a>
		</li>
				<li class="c-author__post">
			<a href="https://au.rollingstone.com/music/music-news/harry-styles-addresses-spitgate-dont-worry-darling-42749/" class="c-author__meta-link c-author__meta-link--post">
				Harry Styles Laughs Off ‘Don’t Worry, Darling’ Drama: I ‘Popped Very Quickly to Venice to Spit on Chris Pine’			</a>
		</li>
			</ul>

	<div class="c-author__view-all">
		<a href="https://au.rollingstone.com/author/lochriec/" class="c-author__meta-link c-author__meta-link--all t-semibold t-semibold--upper t-semibold--loose">View All</a>
	</div>
	</div><!-- .c-author -->
						</div><!-- .c-byline__detail -->
									</div><!-- .c-byline__author -->
				
					<div style="display: inline-block; float: right;">
							</div>
		
	</div><!-- .c-byline__authors -->
</div><!-- .c-byline -->	</div><!-- .l-article-header__block--byline -->

	<div class="l-article-header__block l-article-header__block--share">
			</div><!-- .l-article-header__block--share -->

</header><!-- .l-article-header -->
		
<figure class="c-picture">
	<div class="c-picture__frame">

		<div class="c-crop c-crop--ratio-3x2">
			<img width="900" height="600" src="https://cdn-r2-1.thebrag.com/rs/uploads/2022/09/luke-combs.jpg?resize=900,600&amp;w=450" data-src="https://cdn-r2-1.thebrag.com/rs/uploads/2022/09/luke-combs.jpg?resize=900,600&amp;w=450" class="c-crop__img wp-post-image visible" alt="Luke Combs" data-srcset="https://cdn-r2-1.thebrag.com/rs/uploads/2022/09/luke-combs.jpg?resize=900,600&amp;w=450 450w, https://cdn-r2-1.thebrag.com/rs/uploads/2022/09/luke-combs.jpg?resize=900,600&amp;w=600 600w, https://cdn-r2-1.thebrag.com/rs/uploads/2022/09/luke-combs.jpg?resize=900,600&amp;w=900 900w, https://cdn-r2-1.thebrag.com/rs/uploads/2022/09/luke-combs.jpg?resize=900,600&amp;w=1200 1200w" sizes="(max-width: 450px) 500px, (max-width: 600px) 650px, (max-width: 900px) 950px, (max-width: 1200px) 1250px" srcset="https://cdn-r2-1.thebrag.com/rs/uploads/2022/09/luke-combs.jpg?resize=900,600&amp;w=450 450w, https://cdn-r2-1.thebrag.com/rs/uploads/2022/09/luke-combs.jpg?resize=900,600&amp;w=600 600w, https://cdn-r2-1.thebrag.com/rs/uploads/2022/09/luke-combs.jpg?resize=900,600&amp;w=900 900w, https://cdn-r2-1.thebrag.com/rs/uploads/2022/09/luke-combs.jpg?resize=900,600&amp;w=1200 1200w">		</div><!-- .c-crop -->

	</div>
			<div class="c-picture__caption">

			
							<p class="c-picture__source t-semibold">
					Supplied				</p>
			
		</div><!-- .c-picture__caption -->
	</figure><!-- .c-picture -->
		<div class="l-article-content">


			
			<div class="fc-article-copy">
				
<div class="c-content t-copy">
	<p>US country music superstar <a href="https://au.rollingstone.com/music/music-features/country-star-luke-combs-growin-up-41045/" rel="noopener" target="_blank">Luke Combs</a> has announced an Australia and New Zealand leg of his huge world tour.</p>
<p>The reigning CMA Entertainer of the Year will begin his visit in Auckland on Wednesday, August 9th – his first-ever show in New Zealand – before returning to Australia to perform his biggest headline dates yet (see full dates below). It will be his first performances Down Under since his sold-out 2019 run of shows. Combs will be joined by fellow US country star Cody Johnson and Australian singer-songwriter Lane Pittman.</p>
<p>Tickets go on sale to the general public on Friday, September 16th at 2pm local time. The Frontier Members presale begins on Wednesday, September 14th at 11am local time.</p>
<p>The world tour is in support of Combs’ third studio album <em>Growin’ Up</em>, which reached number two on both the US Billboard 200 and ARIA Albums Chart earlier this year. The album contained tracks like “The Kind of Love We Make”, which debuted at number 17 on the Billboard Country Airplay Chart.</p>
<p>Combs’ music has achieved unanimous acclaim. “His detail-rich songs make <em>Growin’ Up</em> a big-time country album with a tip-jar-worthy intimacy,” <em>Rolling Stone</em> hailed.&nbsp;<em><br>
</em></p>
<p>“One of country music’s biggest stars…He’s accomplished this not with virtuosic musical innovation, genre-crossing celebrity collaborations or by hosting a television singing competition, but by cranking out one irrepressibly catchy, widely relatable meat-and-potatoes country anthem after another,” <em>The New York Times</em> wrote about the singer-songwriter.&nbsp;<em><br>
</em></p>
<figure class="op-interactive"><iframe loading="lazy" title="Luke Combs - The Kind of Love We Make (Official Music Video)" width="500" height="281" src="https://www.youtube.com/embed/x8Mc8Pbl06g?feature=oembed" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" data-gtm-yt-inspected-6="true"></iframe></figure>
<h3 style="text-align: center;">Luke Combs 2023 Australia and New Zealand Tour</h3>
<p style="text-align: center;">Presented by Chugg Entertainment and Frontier Touring</p>
<p style="text-align: center;">With special guests Cody Johnson and Lane Pittman</p>
<p style="text-align: center;"><a href="https://www.frontiertouring.com/lukecombs" rel="noopener" target="_blank">Frontier Members</a> presale begins Wednesday, September 14th (11am local time)<br>
General public sale begins Friday, September 16th (2pm local time)</p>
<p style="text-align: center;"><strong>Wednesday, August 9th</strong><br>
Spark Arena, Auckland, NZ<br>
Tickets: <a href="http://frnter.co/LC23ALK" rel="noopener" target="_blank">Ticketmaster</a></p>
<p style="text-align: center;"><strong>Friday, August 11th</strong><br>
Brisbane Entertainment Centre, Brisbane, QLD<br>
Tickets: <a href="http://frnter.co/LC23BRI" rel="noopener" target="_blank">Ticketek</a></p>
<p style="text-align: center;"><strong>Wednesday, August 16th</strong><br>
Qudos Bank Arena, Sydney, NSW<br>
Tickets: <a href="http://frnter.co/LC23SYD" rel="noopener" target="_blank">Ticketek</a></p>
<p style="text-align: center;"><strong>Sunday, August 20th</strong><br>
Rod Laver Arena, Melbourne, VIC<br>
Tickets: <a href="http://frnter.co/LC23MEL" rel="noopener" target="_blank">Ticketek</a></p>
<p style="text-align: center;"><strong>Wednesday, August 23rd</strong><br>
AEC Arena, Perth, WA<br>
Tickets: <a href="http://frnter.co/LC23ADE" rel="noopener" target="_blank">Ticketek</a></p>
<p style="text-align: center;"><strong>Saturday, August 26th</strong><br>
RAC Arena, Perth, WA<br>
Tickets: <a href="http://frnter.co/LC23PER" rel="noopener" target="_blank">Ticketek</a></p>

</div><!-- .c-content -->

		</div><!-- .l-article-content -->
	</article>
			</main>

		<aside class="l-blog__secondary">
			<div style="margin-bottom: 2rem;"><?php ThemeSetup::render_ads('mrec', '', 300); ?></div>
			<?php dynamic_sidebar('article_right_sidebar'); ?>
			<div class="sticky-side-ads-wrap" style="position: sticky; top: 95px; margin-top: 1rem;">
				<?php ThemeSetup::render_ads('vrec', '', 300); ?>
			</div>
		</aside><!-- .l-blog__secondary -->
	</div>
	<div style="border-top: 1px solid #ccc;">
		<div class="l-blog" style="display: flex; align-content: center; justify-content: center;">
			<div>
				<img src="https://cdn.thebrag.com/tbm/The-Brag-Media-300px.png" style="width: 200px;" />
			</div>
		</div>
		<div class="c-cards-grid l-blog" id="feed">
		<div class="load-more"><div class="spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div></div>
		</div>
	</div>
</div><!-- .l-page__content -->
<?php get_template_part('template-parts/footer/footer'); ?>
<?php

$body = [
	'canvas_id' => 'af10e838-d374-a114-7fce-1e072eb5eb04',
	'broadcast' => true,
	'canvas_entry_properties' => [
	  'title' => 'Jack Harlow says he lost his virginity not once but twice',
	  'message' => 'Jack Harlow got very honest about his sexual history with the crowd at his Nashville concert.',
	  'url' => 'https://thebrag.com/this-wonderful-tiktok-account-finds-romance-in-everyday-melbourne/',
	  'image_url' => 'https://tonedeaf.thebrag.com/img-socl/?url=https://images.thebrag.com/td/uploads/2022/05/Drake-Jack-Harlow-Kentucky-Derby-e1662958117888.jpg',
	  'category' => 'HIP HOP'
	]
  ];

//   $body = wp_json_encode($body);

//   $resp = wp_remote_post(
// 	'https://rest.iad-05.braze.com/canvas/trigger/send',
// 	[
// 	  'headers' => [
// 		'Content-Type' => 'application/json',
// 		'Authorization' => 'Bearer ' . '4bdcad2b-f354-48b5-a305-7a9d77eb356e'
// 	  ],
// 	  'body' => $body,
// 	]
//   );

//   echo '<pre>';
//   var_dump($resp);
//   echo '</pre>';

get_footer();
