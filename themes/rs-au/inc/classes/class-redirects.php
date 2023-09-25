<?php
/**
 * Redirects
 *
 * Set up redirects for the theme.
 *
 * @package pmc-rollingstone-2018
 * @since   2018-05-17
 */

namespace Rolling_Stone\Inc;

use PMC;
use PMC\Global_Functions\Traits\Singleton;

class Redirects {

	use Singleton;

	const CACHE_KEY_PREFIX = 'rollingstone_redirects';
	const CACHE_GROUP      = 'rollingstone_redirects_v1';

	/**
	 * The path of the unredirected URL.
	 *
	 * @var string
	 */
	public $path;

	/**
	 * Direct redirects. No regex required. Trailing slash is optional.
	 *
	 * @var array
	 */
	public $one_to_one_redirects = array(
		'/the-new-classics'                               => '/interactive//the-new-classics/',
		'/features/levis-iconic-denim-then-and-now'       => '/interactive/levis-iconic-denim-then-and-now/',
		'/the-photo-issue'                                => '/interactive/the-photo-issue/',
		'/music/lists/100-greatest-songwriters'           => '/interactive/lists-100-greatest-songwriters/',
		'/culture/features/the-100-best-instagram-accounts' => '/interactive/features-the-100-best-instagram-accounts/',
		'/culture/lists/cult-classics-a-z'                => '/interactive/lists-cult-classics-a-z/',
		'/politics/news/us-presidential-election-candidates-2016' => '/interactive/news-us-presidential-election-candidates-2016/',
		'/politics/pictures/donald-trumps-impact-presidential-election-2016' => '/interactive/pictures-donald-trumps-impact-presidential-election-2016/',
		'/dos-equis/most-interesting-man-in-the-world-final-interview' => '/interactive/most-interesting-man-in-the-world-final-interview/',
		'/culture/off-the-beaten-path/nashville'          => '/interactive/off-the-beaten-path-nashville/',
		'/culture/off-the-beaten-path/austin'             => '/interactive/off-the-beaten-path-austin/',
		'/culture/features/25-incredible-reinventions-in-pop-culture' => '/interactive/features-25-incredible-reinventions-in-pop-culture/',
		'/most-groundbreaking-albums-of-all-time'         => '/interactive/most-groundbreaking-albums-of-all-time/',
		'/movies/features/oscars-academy-awards-winners-speeches-time-played-off' => '/interactive/features-oscars-academy-awards-winners-speeches-time-played-off/',
		'/culture/features/50-most-iconic-looks-in-music' => '/interactive/features-50-most-iconic-looks-in-music/',
		'/artists-going-green'                            => '/interactive/artists-going-green/',
		'/music/features/goodnight-spoon-keith-richards-lost-kiddie-classic' => '/interactive/music-features-goodnight-spoon-keith-richards-lost-kiddie-classic/',
		'/feature/gun-control/survivor-detail/santokh-singh' => '/interactive/feature-gun-control-survivor-detail-santokh-singh/',
		'/feature/gun-control/survivor-detail/antonius-wiriadjaja' => '/interactive/feature-gun-control-survivor-detail-antonius-wiriadjaja/',
		'/feature/gun-control/survivor-detail/jean-eason' => '/interactive/feature-gun-control-survivor-detail-jean-eason/',
		'/feature/gun-control/survivor-detail/jennifer-longdon' => '/interactive/feature-gun-control-survivor-detail-jennifer-longdon/',
		'/feature/gun-control/survivor-detail/kate-ranta' => '/interactive/feature-gun-control-survivor-detail-kate-ranta/',
		'/feature/gun-control/survivor-detail/stephen-barton' => '/interactive/feature-gun-control-survivor-detail-stephen-barton/',
		'/feature/gun-control/survivor-detail/clai-lasher-sommers' => '/interactive/feature-gun-control-survivor-detail-clai-lasher-sommers/',
		'/feature/gun-control/survivor-detail/mindy-finkelstein' => '/interactive/feature-gun-control-survivor-detail-mindy-finkelstein/',
		'/feature/gun-control/survivor-detail/khayree-reid' => '/interactive/feature-gun-control-survivor-detail-khayree-reid/',
		'/feature/gun-control/survivor-detail/benedict-jones' => '/interactive/feature-gun-control-survivor-detail-benedict-jones/',
		'/feature/gun-control/americas-gun-violence-epidemic' => '/interactive/feature-gun-control-americas-gun-violence-epidemic/',
		'/feature/gun-control/survivor-gallery'           => '/interactive/feature-gun-control-survivor-gallery/',
		'/feature/gun-control/map'                        => '/interactive/feature-gun-control-map/',
		'/feature/belly-beast-meat-factory-farms-animal-activists' => '/interactive/feature-belly-beast-meat-factory-farms-animal-activists/',
		'/politics/threat-assessment-20140228'            => '/interactive/politics-threat-assessment-20140228/',
		'/politics/threat-assessment-20140221'            => '/interactive/politics-threat-assessment-20140221/',
		'/feature/motorcycles/history-of-motorcycles'     => '/interactive/feature-motorcycles-history-of-motorcycles/',
		'/politics/threat-assessment-20140214'            => '/interactive/politics-threat-assessment-20140214/',
		'/movies/top-tv-moment-heartstoppers'             => '/interactive/movies-top-tv-moment-heartstoppers/',
		'/feature/millennial-sexual-revolution-relationships-marriage' => '/interactive/feature-millennial-sexual-revolution-relationships-marriage/',
		'/feature/the-geeks-on-the-frontlines'            => '/interactive/feature-the-geeks-on-the-frontlines/',
		'/feature/jerky-boys-prank-calls-comedy-legends-comeback' => '/interactive/feature-jerky-boys-prank-calls-comedy-legends-comeback/',
		'/feature/greenland-melting'                      => '/interactive/feature-greenland-melting/',
		'/feature/a-team-killings-afghanistan-special-forces' => '/interactive/feature-a-team-killings-afghanistan-special-forces/',
		'/city-streets/six-streets-that-changed-music-history' => '/interactive/city-streets-six-streets-that-changed-music-history/',
		'/netflix/some-hard-truth-with-bojack-horseman'   => '/interactive/netflix-some-hard-truth-with-bojack-horseman/',
		'/hip-hop'                                        => '/t/hip-hop',
		'/cannabis'                                       => '/e/cannabis',
		'/50-years-of-music-style'                        => '/e/50-years-of-music-style',
		'/american-beauty'                                => '/e/american-beauty',
		'/50th-anniversary'                               => '/e/50th-anniversary',
		'/had-to-be-there'                                => '/e/had-to-be-there',
		'/country/ram-report'                             => '/e/ram-report',
		'/country/rs-country-sessions'                    => '/e/rs-country-sessions',
		'/music/momentum-a-day-in-the-life'               => '/e/momentum-a-day-in-the-life',
	);

	/**
	 * This array contains a one-to-one map of redirects for Glixel section.
	 * By default all Glixel URLs are directed to one specific URL but the URIs
	 * in this array override that behaviour and redirect to different destinations each.
	 *
	 * @var array
	 */
	protected $_glixel_one_to_one_redirects = [
		'/glixel/features/lightwear-introducing-magic-leaps-mixed-reality-goggles-w514479'    => 'https://variety.com/2017/gaming/news/magic-leap-impressions-interview-1202870280/',
		'/glixel/features/weta-gameshop-dr-grordborts-ray-guns-and-magic-leap-gaming-w517077' => 'https://variety.com/2018/gaming/features/weta-gameshop-dr-grordborts-game-1202870315/',
		'/glixel/features/the-blood-and-bone-behind-magic-leap-w514628'                       => 'https://variety.com/2017/gaming/features/magic-leap-mako-surgical-corp-1202897721/',
		'/glixel/features/magic-leap-unveils-madefire-powered-mixed-reality-comics-w507695'   => 'https://variety.com/2017/gaming/features/magic-leap-madefire-2-1202897730/',
		'/glixel/features/magic-leap-10-more-things-you-didnt-know-w514535'                   => 'https://variety.com/2017/gaming/features/magic-leap-10-more-things-1202897742/',
		'/glixel/features/neopets-a-look-into-early-2000s-girl-culture-w509885'               => 'https://variety.com/2017/gaming/features/neopets-internet-girl-culture-1202897761/',
		'/culture/news/glixel.com' => 'https://variety.com/v/gaming/',
	];

	/**
	 * White list of category slugs from the old site.
	 *
	 * @var array
	 */
	public $legacy_category_slugs = array(
		'country',
		'culture',
		'movies',
		'music',
		'politics',
		'tv',
	);

	/**
	 * White list of subcategory slugs from the old site.
	 *
	 * @var array
	 */
	public $legacy_subcategory_slugs = array(
		'albumreviews',
		'artists',
		'features',
		'lists',
		'live-reviews',
		'news',
		'pictures',
		'premieres',
		'reviews',
		'recaps',
		'videos',
	);

	/**
	 * Legacy tags that were not under the /topics directory.
	 *
	 * @var array
	 */
	public $legacy_tag_patterns = [
		'10artists',
		'2018-midterms',
		'25under25',
		'50-years-of-music-style',
		'50th-anniversary',
		'a-prairie-home-companion',
		'aa',
		'aaron-lewis',
		'acdc',
		'aclu',
		'al-franken',
		'alan-sepinwall',
		'alden-ehrenreich',
		'alicia-keys',
		'alt-right',
		'amanda-shires',
		'american-beauty-2',
		'american-young',
		'anti-semitism',
		'artist-you-need-to-know',
		'ashley-campbell',
		'aubrie-sellers',
		'auto',
		'back-to-top',
		'barackobama',
		'benicio-del-toro',
		'betsy-devos',
		'beyonce',
		'billy-currington',
		'billy-gilman',
		'billy-gilman',
		'billy-ray-cyrus',
		'black-crowes',
		'black-friday',
		'black-mirror',
		'black-panther',
		'blackberry-smoke',
		'blink-182',
		'bob-dylan',
		'bob-weir',
		'bobby-bones',
		'bobdylan',
		'bonovox',
		'books',
		'brad-paisley',
		'brandy-clark',
		'brantley-gilbert',
		'brent-cobb',
		'brett-ratner',
		'brian-setzer',
		'brothers-osborne',
		'butch-walker',
		'caitlyn-smith',
		'cam',
		'cardi-b',
		'carrie-coon',
		'cassadee-pope',
		'chadwick-boseman',
		'charlie-daniels',
		'charlottesville',
		'chase-bryant',
		'chelsea-manning',
		'chris-janson',
		'chris-robinson',
		'chris-stapleton',
		'chris-thile',
		'chris-young',
		'christopher-guest',
		'clare-dunn',
		'clint-black',
		'cmafest',
		'cody-jinks',
		'consent-education',
		'cory-booker',
		'countryram-report',
		'countryrs-country-sessions',
		'coverwall',
		'craig-campbell',
		'culture-index',
		'cyberwarfare',
		'daisy-ridley',
		'dan-shay',
		'dawes',
		'deals',
		'death-penalty',
		'demi-lovato',
		'destiny-2',
		'devil-makes-three',
		'dice',
		'dierks-bentley',
		'dixie-chicks',
		'dolly-parton',
		'dr-john',
		'drive-by-truckers',
		'dwight-yoakam',
		'edgar-wright',
		'electra-mustaine',
		'elise-davis',
		'elle-king',
		'elon-musk',
		'emily-west',
		'eminem',
		'emmylou-harris',
		'eric-church',
		'esports',
		'faith-hill',
		'felicity-jones',
		'florida-georgia-line',
		'frances-mcdormand',
		'garth-brooks',
		'gdc',
		'gender',
		'george-strait',
		'gift-guide',
		'glen-campbell',
		'glixel-feature',
		'glixel-news',
		'glixel-opinion',
		'glixel-review',
		'glixel',
		'greatest-photographs',
		'gregg-allman',
		'grist',
		'had-to-be-there',
		'harvey-weinstein',
		'hillary-scott',
		'hurricane-harvey',
		'hurricane-irma',
		'imdying',
		'imdyingvideo',
		'intersex',
		'issa-rae',
		'ivanka-trump',
		'jack-ingram',
		'jake-gyllenhaal',
		'jake-owen',
		'james-comey',
		'james-corden',
		'james-mcmurtry',
		'james-taylor',
		'jana-kramer',
		'jared-kushner',
		'jason-aldean',
		'jason-isbell',
		'jean-shepard',
		'jeff-sessions',
		'jessie-james-decker',
		'jim-lauderdale',
		'joey-rory',
		'john-anderson',
		'john-carpenter',
		'john-fogerty',
		'john-hiatt',
		'john-malkovich',
		'john-paul-white',
		'john-prine',
		'john-waters',
		'johnny-cash',
		'jon-pardi',
		'jonbenet-ramsey',
		'jonny-fritz',
		'jordan-peele',
		'joseph-jaafari',
		'justin-moore',
		'justin-trudeau',
		'kacey-jones',
		'kacey-jones',
		'kacey-musgraves',
		'keith-ellison',
		'keith-urban',
		'kelsea-ballerini',
		'kendrick-lamar',
		'kenny-chesney',
		'kid-rock',
		'kip-moore',
		'kumail-nanjiani',
		'landslidetestrs',
		'latin',
		'laura-dern',
		'league-of-legends',
		'leann-rimes',
		'lee-ann-womack',
		'lee-brice',
		'legal-pot-guide',
		'linda-ronstadt',
		'little-big-town',
		'locash',
		'luke-bryan',
		'luke-combs',
		'lydia-loveless',
		'magic-leap',
		'manchester-bombing',
		'marc-ford',
		'maren-morris',
		'margo-price',
		'margo-price',
		'mary-tyler-moore',
		'megyn-kelly',
		'merle-haggard',
		'metoo',
		'michael-jackson',
		'michelle-wolf',
		'mike-myers',
		'millennials',
		'miranda-lambert',
		'mo-pitney',
		'muddy-magnolias',
		'music-industry',
		'musicmomentum-a-day-in-the-life',
		'natalie-portman',
		'nathaniel-rateliff',
		'naughty-dog',
		'neal-mccoy',
		'neil-gorsuch',
		'neil-young',
		'nick-jonas',
		'nick-offerman',
		'old-dominion',
		'opioid-epidemic',
		'pam-tillis',
		'paris-jackson',
		'patsy-cline',
		'pax-east',
		'pink',
		'playstation-4',
		'presidential-inauguration-2017',
		'randomnotes',
		'rami-malek',
		'randy-rogers-band',
		'rascal-flatts',
		'reba-mcentire',
		'reckless-kelly',
		'rhiannon-giddens',
		'rich-robinson',
		'robert-mueller',
		'ronnie-dunn',
		'rosanne-cash',
		'roseanne-barr',
		'ryan-adams',
		'ryan-bingham',
		'ryan-follese',
		'ryan-reynolds',
		's-town',
		'sam-smith',
		'sara-watkins',
		'sarah-jarosz',
		'science',
		'scientology',
		'sean-spicer',
		'sexual-harassment',
		'sexual-misconduct',
		'shania-twain',
		'shawn-colvin',
		'shelley-skidmore',
		'sheryl-crow',
		'shura',
		'siriusbeatles',
		'skip-marley',
		'slender-man',
		'songs-of-the-summer-2018',
		'stan-lee',
		'standing-rock',
		'star-wars-the-last-jedi',
		'steve-bannon',
		'steve-earle',
		'steve-martin',
		'sting',
		'stormy-daniels',
		'stranger-things',
		'sturgill-simpson',
		'taylor-swift',
		'the-band-perry',
		'the-cadillac-three',
		'the-cadillac-three',
		'the-carter-family',
		'the-isaacs',
		'the-kentucky-headhunters',
		'the-mavericks',
		'the-string-cheese-incident',
		'the-young-pope',
		'thomas-rhett',
		'thompson-square',
		'tiffany-haddish',
		'tim-kaine',
		'tim-mcgraw',
		'tim',
		'toby-keith',
		'tom-perez',
		'tom-wolfe',
		'townes-van-zandt',
		'turnpike-troubadours',
		'twitch',
		'twix',
		'uncharted-4',
		'uncharted-5',
		'vince-gill',
		'virtual-reality',
		'walking-the-floor',
		'wayne-hancock',
		'westworld',
		'wild-feathers',
		'william-tyler',
		'willie-nelson',
		'winona-ryder',
		'women-in-culture',
		'womens-march',
		'wynonna-judd',
		'yearend-2017',
		'yonder-mountain-string-band',
	];

	/**
	 * Sets up path variable and adds hooks.
	 */
	public function __construct() {

		// Don't redirect admin pages.
		if ( is_admin() ) {
			return;
		}

		// This won't work on a subcategory multisite installation.
		if ( is_multisite() && defined( 'SUBDOMAIN_INSTALL' ) && false === SUBDOMAIN_INSTALL ) {
			return;
		}

		/**
		 * Filters the one-to-one redirects to check URLs against.
		 *
		 * @param array An associative array of redirects. No wildcards.
		 */
		$this->one_to_one_redirects = apply_filters( 'rollingstone_one_to_one_redirects', $this->one_to_one_redirects );

		$this->path = PMC::filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL );

		// We won't need to redirect the homepage.
		if ( empty( $this->path ) || '/' === $this->path ) {
			return;
		}

		$this->_setup_hooks();

	}

	/**
	 * Method to setup listeners with WP hooks
	 *
	 * @return void
	 */
	protected function _setup_hooks() {

		/*
		 * Actions
		 */
		add_action( 'wp', [ $this, 'canonical_redirect' ], 1 );

		// Redirect glixel pages as early as possible.
		add_action( 'init', [ $this, 'maybe_redirect_glixel' ], 5 );

		// Handle redirects which are not setup in wpcom-legacy-redirector
		// like legacy post and archive URLs
		add_action( 'init', [ $this, 'perform_redirects' ], 11 );


		/*
		 * Filters
		 */
		add_filter( 'allowed_redirect_hosts', [ $this, 'add_to_allowed_hosts' ] );
		add_filter( 'rollingstone_redirect_destination_url', [ $this, 'maybe_setup_glixel_one_to_one_redirects' ], 10, 2 );

	}

	/**
	 * Sanitizes a legacy tag for use in regex.
	 *
	 * @param string $tag A tag.
	 * @return string The sanitized tag.
	 */
	public function sanitize_legacy_tag( $tag = '' ) {

		$tag = strval( $tag );
		$tag = preg_quote( $tag );

		return $tag;
	}

	/**
	 * Provides a list of archive-related patterns to match against the current URL.
	 *
	 * @return array
	 */
	public function get_redirect_patterns() {

		$category_slugs      = implode( '|', $this->legacy_category_slugs );
		$subcategory_slugs   = implode( '|', $this->legacy_subcategory_slugs );
		$legacy_tag_patterns = implode( '|', array_map( [ $this, 'sanitize_legacy_tag' ], (array) $this->legacy_tag_patterns ) );

		$redirect_patterns = [];

		$one_to_one_archive_redirects = [
			'services/(sitemap|subscription)'              => '/',
			'(forums|issue[0-9]{1,}|latestissue|login_form|login|logout|widgets)' => '/',
			'plus/archive'                                 => '/',
			'long-reads'                                   => 'e/long-reads',
			'news/coverstory'                              => 't/cover-story',
			'news/coverstory/([0-9]{1,}|500songs|50centkanyewestcover)' => 't/cover-story',
			'randomnotes'                                  => 't/random-notes',
			'musiccenter\.org/sleep'                       => 't/sleep',
			'music/videos/video-Gallery-page/([0-9]{1,})'  => 'video',
			'music/videos/Wolf'                            => 'video',
			'music/news/(b|v|com|edit|k|March|Mirning|introduci|a-report-on|explained|vvforums\.com)' => 'music/music-news',
			'music/music-now'                              => 'music/music-news',
			'music/photos/?([0-9]{1,})?/?([0-9]{1,})?/?([0-9]{1,1})?' => 'music/music-pictures',
			'photos'                                       => 'music/music-pictures',
			'photos/(archive|gallery(/_/id)?/([0-9]{1,}))' => 'music/music-pictures',
			'news/story/([0-9]{1,})/(100_best_albums_of_the_decade|the_25_best_albums_of_2009)/?([0-9]{1,})?' => 'music/music-lists/100-best-albums-of-the-2000s-153375',
			'news/story/([0-9]{1,})/(100_best_songs_of_the_decade)/?([0-9]{1,})?' => 'music/music-lists/100-best-songs-of-the-2000s-153056',
			'news/newsarticle\.asp'                        => 'music/music-news',
			'news/story/(_/id/|id/)?([0-9]{1,})/?(.*)'     => 'music/music-news',
			'recordings/review\.asp'                       => 'music/music-album-reviews',
			'reviews/album'                                => 'music/music-album-reviews',
			'reviews/album(/_/id)?/([0-9]{1,})/?((rid|review|rammstein)/([0-9]{1,})?)?' => 'music/music-album-reviews',
			'culture/news/(a|Added|beatthang\.com|blockworksmc\.com|bobwoodrufffoundation\.org)' => 'culture/culture-news',
			'culture/photos/([0-9]{1,})/([0-9]{1,})/?([0-9]{1,})?' => 'culture/culture-lists',
			'mv_reviews/review\.asp'                       => 'movies/movie-reviews',
			'movies/reviews'                               => 'movies/movie-reviews',
			'movies/lists'                                 => 'movies/movie-lists',
			'movies/reviews/([0-9]{1,})/([0-9]{1,})'       => 'movies/movie-reviews',
			'reviews/movie(/review\.asp|/default\.asp)?'   => 'movies/movie-reviews',
			'reviews/(movie|dvd)(/_/id)?/([0-9]{1,})/?((rid|review)/([0-9]{1,})?)?' => 'movies/movie-reviews',
			'politics/story(/_/id)?/([0-9]{1,})'           => 'politics/politics-news',
			'politics/news/([0-9]{1,})/([0-9]{1,})'        => 'politics/politics-news',
			'(site_index|sitemap)/movies/news_([0-9]{4,4}-[0-9]{2,2})' => 'movies/movie-news',
			'(site_index|sitemap)/(sports)/news_([0-9]{4,4}-[0-9]{2,2})' => 'culture/culture-sports',
			'(site_index|sitemap)/(glixel)/news_([0-9]{4,4}-[0-9]{2,2})' => 'culture/culture-news',
			'(site_index|sitemap)/(?P<category>[\w-]+)/news_([0-9]{4,4}-[0-9]{2,2})' => '$category/$category-news',
			'sitemap/(?P<category>[\w-]+)/([0-9]{4,4}\.htm[l]?)' => '$category/$category-news',
			'videos/player/([0-9]{1,})'                    => 'video',
			'videos/video/(_/id/)?([0-9]{1,})'             => 'video',
			'video/(?P<slug>[0-9]{1,}+)/fallback'          => 'video/$slug',
			'sports/(features)/?(edit)?'                   => 'culture/culture-sports',

			'culture/features/(back-to-top|doubl|index|mikes-hard-lemonade-([0-9]{1,1})|target|the-p|trent-reznor|vanilla-ice|victoria-beckham)' => 'culture/culture-features',
			'culture/features/(fiat\.com|mikesouth\.com)'  => 'culture/culture-news',

			'videos/rss'                                   => 'custom-feed/videos',
			'songreviews/rss'                              => 'music/music-live-reviews/feed',
			'albumreviews/rss'                             => 'music/music-album-reviews/feed',
			'reviews/rss'                                  => 'movies/movie-reviews/feed',
			'sports/rss'                                   => 'culture/culture-sports/feed',

			// Author archives.
			'contributor/(?P<slug>[\w-]+)'                 => '/author/$slug',

			"(?P<slug>($legacy_tag_patterns))"             => 't/$slug',

			// Redirect several country subcategories to music-country.
			'country/(news|features|premieres|live-reviews|songreviews)' => 'music/music-country',

			// Combine all music album reviews into single category.
			'(music|country)/(reviews|albumreviews|songreviews)' => 'music/music-album-reviews',

			// /premieres goes to news.
			"(?P<category>($category_slugs))/premieres"    => '$category/$category-news',

			// Old /video subcategories are now /videos.
			"(?P<category>($category_slugs))/video"        => '$category/$category-videos',

			// All recaps are in TV.
			'(country|culture|tv|music)/recaps'            => 'tv/tv-recaps',

			// Game reviews redirect to movie reviews.
			'movies/gamereviews'                           => 'movies/movie-reviews',

			// Sports are in culture now.
			'sports/(news|features)'                       => 'culture/culture-sports',
			'sports/(?P<subcategory>(videos|lists|pictures))' => 'culture/culture-$subcategory',
			'sports'                                       => 'culture/culture-sports',

			// The movie subcategory slug is singular.
			"movies/(?P<subcategory>($subcategory_slugs))(/lige)" => 'movies/movie-$subcategory',

			// Redirect country subcategories not matched above.
			"country/(?P<subcategory>($subcategory_slugs))" => 'music/music-country-$subcategory',

			// Most general /category/subcategory redirect.
			"(?P<category>($category_slugs))/(?P<subcategory>($subcategory_slugs))" => '$category/$category-$subcategory',
		];

		// To add support of pagination on each paten.
		foreach ( $one_to_one_archive_redirects as $from => $destination ) {

			$from = str_replace( '/', '\/', $from );

			$redirect_patterns[ "/^\/$from\/?\?(?:.*&)?page=(?P<page>[0-9]{1,})$/i" ] = sprintf( '/%s/page/$page', $destination );
			$redirect_patterns[ "/^\/$from\/page\/(?P<page>[0-9]{1,})\/?$/i" ]        = sprintf( '/%s/page/$page', $destination );
			$redirect_patterns[ "/^\/$from\/?([\?].*)?$/i" ]                          = sprintf( '/%s/', $destination );

		}

		$redirect_patterns['/(?P<slug>(.+))\/window\.print(?:\(\))?\/?$/mi'] = '$slug';

		return $redirect_patterns;

	}

	/**
	 * Called by 'init' hook (later than 10 priority so that Rewrites
	 * class gets to setup custom rewrite rules etc), this method
	 * handles redirects which are not setup in wpcom-legacy-redirector
	 * like legacy post and archive URLs.
	 *
	 * @return void
	 */
	public function perform_redirects() {

		// Redirect a singular post, if needed.
		$this->maybe_redirect_post_from_legacy_path();

		// Redirect an archive, if needed.
		$this->maybe_redirect_archive();

	}

	/**
	 * Converts a URL path to an md5 hash.
	 *
	 * This function must match the corresponding function in the data import script.
	 *
	 * @param string $slug The URL path.
	 * @return string An md5 hash.
	 */
	public static function path_to_md5( $path ) {

		$path = strtolower( $path );
		$path = untrailingslashit( $path );

		return md5( $path );

	}

	/**
	 * Checks for the md5 hash of the current path in the WP post meta table.
	 * Legacy URL slugs are also stored as md5 hash in corresponding
	 * post meta as meta-key. This is used to do a quick lookup
	 * using old URL to see if any existing legacy URL has a corresponding
	 * post in the database or not.
	 *
	 * Meta key is used because in post meta table, meta key fields are indexed
	 * which result in a very significant performance gain over meta value lookup
	 * which is highly discouraged both by us (PMC Engg) and VIP.
	 *
	 * @param string $path URI path which is used to find a matching post
	 *
	 * @return bool|string The permalink of the post if found; false otherwise.
	 */
	public function get_uncached_wp_permalink_from_legacy_path( $path = '' ) {

		if ( empty( $path ) || ! is_string( $path ) ) {
			return false;
		}

		global $wpdb;

		$path_hash      = static::path_to_md5( $path );
		$post_permalink = false;

		/*
		 * Grab the post ID from post meta table using a direct SQL lookup
		 * as it is faster than using WP_Query especially since the lookup
		 * is based on a meta key and we just need the post ID here.
		 *
		 * Ignoring the below line in phpcs because it is incorrectly flagging
		 * it and asking to use $wpdb->prepare() even when it is being used.
		 */
		$sql     = sprintf( 'SELECT post_id FROM %s WHERE meta_key=%%s LIMIT 0, 1', $wpdb->postmeta );
		$post_id = $wpdb->get_var( $wpdb->prepare( $sql, $path_hash ) );    // @codingStandardsIgnoreLine

		if ( ! empty( $post_id ) && intval( $post_id ) > 0 ) {
			// Legacy slug has matching post.
			// We got the post ID, lets use it to grab post permalink.
			$post_permalink = get_the_permalink( intval( $post_id ) );
		}

		return $post_permalink;

	}

	/**
	 * Checks for a cached permalink and attempts to fetch it if it doesn't exist.
	 *
	 * @return bool|string The permalink of the post if found; false otherwise.
	 */
	public function get_wp_permalink_from_legacy_path( $legacy_slug = '' ) {

		$cache_key = sprintf( '%s_from_legacy_path_%s', self::CACHE_KEY_PREFIX, $legacy_slug );

		$cache = new \PMC_Cache( $cache_key, self::CACHE_GROUP );

		return $cache->expires_in( MONTH_IN_SECONDS )
						->updates_with( [ $this, 'get_uncached_wp_permalink_from_legacy_path' ], [ $legacy_slug ] )
						->get();

	}

	/**
	 * Called on 'init', this method looks up current URI in the post meta to see
	 * if the URI is associated with any post as legacy URI. If it finds a match
	 * then it performs a 301 redirect to the new URL of the post.
	 *
	 * @return void
	 */
	public function maybe_redirect_post_from_legacy_path() {

		$path_parts     = $this->parse_wp_path( $this->path );
		$post_permalink = $this->get_wp_permalink_from_legacy_path( $path_parts['slug'] );

		if ( empty( $post_permalink ) ) {
			return;
		}

		if ( ! empty( $path_parts['query'] ) ) {
			$path_parts['query'] = '?' . $path_parts['query'];
		}

		$destination_url = sprintf( '%s%s%s', $post_permalink, $path_parts['endpoint'], $path_parts['query'] );

		$this->redirect_to( $destination_url, 301 );

	}

	/**
	 * If a destination URL is found, passes it to the redirect method. Otherwise, do nothing.
	 */
	public function maybe_redirect_archive() {

		$destination = $this->get_destination_url();

		if ( empty( $destination ) ) {
			return;
		}

		$this->redirect_to( $destination );

	}

	/**
	 * Allows safe redirect to redirect to variety.com.
	 *
	 * @param array $hosts
	 * @return void
	 */
	public function add_to_allowed_hosts( $hosts = [] ) {

		if ( empty( $hosts ) ) {
			$hosts = [];
		}

		$hosts[] = 'variety.com';
		$hosts[] = 'pmc.com';
		$hosts[] = 'w1.buysub.com';
		$hosts[] = 'buysub.com';
		$hosts[] = 'subscribe.rollingstone.com';
		$hosts[] = 'archive.rollingstone.com';
		$hosts[] = 'add.my.yahoo.com';
		$hosts[] = 'assets.rollingstone.com';
		$hosts[] = 'feature.rollingstone.com';
		$hosts[] = 'open.spotify.com';
		$hosts[] = 'pages.email.rollingstone.com';
		$hosts[] = 'rollingstoneextras.com';
		$hosts[] = 'surveys.wennermedia.com';
		$hosts[] = 'www.rockpaperphoto.com';
		$hosts[] = 'www1.rollingstone.com';
		$hosts[] = 'auth.archive.rollingstone.com';
		$hosts[] = 'itunes.apple.com';
		$hosts[] = 'secure.customersvc.com';
		$hosts[] = 'www.rollingstonesubscriptions.com';
		$hosts[] = 'rollingstonesubscriptions.com';

		return $hosts;

	}

	/**
	 * Redirects URLs with paths beginning with /glixel to the Gaming vertical on the variety site.
	 */
	public function maybe_redirect_glixel() {

		if ( 0 === strpos( $this->path, '/glixel' ) ) {

			$this->redirect_to( 'https://variety.com/v/gaming/', 301 );

		}

	}

	/**
	 * Tries to get a destination url from the class $path variable.
	 *
	 * @return string|false A URL if found; false otherwise.
	 */
	public function get_destination_url() {

		// Current path is a direct match.
		if ( isset( $this->one_to_one_redirects[ $this->path ] ) ) {
			return trailingslashit( site_url( $this->one_to_one_redirects[ $this->path ] ) );
		}

		// Current path + trailing slash is a direct match.
		if ( isset( $this->one_to_one_redirects[ "$this->path/" ] ) ) {
			return trailingslashit( site_url( $this->one_to_one_redirects[ "$this->path/" ] ) );
		}

		$redirect_patterns = $this->get_redirect_patterns();

		foreach ( (array) $redirect_patterns as $source_pattern => $destination_template ) {

			preg_match( $source_pattern, $this->path, $matches );

			if ( empty( $matches ) ) {
				continue;
			}

			// Fill nonexisting values with an empty string.
			$matches = wp_parse_args(
				$matches,
				array(
					'category'    => '',
					'subcategory' => '',
					'slug'        => '',
					'page'        => '',
				)
			);

			$replacements = array(
				'$category'    => $matches['category'],
				'$subcategory' => $matches['subcategory'],
				'$slug'        => $matches['slug'],
				'$page'        => $matches['page'],
			);

			$destination = str_replace(
				array_keys( (array) $replacements ),
				array_values( (array) $replacements ),
				$destination_template
			);

			if ( $this->path === $destination ) {
				return false;
			}

			// There could be up to three consecutive slashes.
			$destination = str_replace( array( '///', '//' ), '/', $destination );

			return trailingslashit( site_url( $destination ) );

		}

		return false;
	}

	/**
	 * Redirects to the provided URL.
	 *
	 * @param string $destination_url A URL to redirect to.
	 * @param int    $redirect_status Defaults to 301 which is for permanent redirect
	 *
	 * @return void
	 */
	public function redirect_to( $destination_url, $redirect_status = 301 ) {

		/**
		 * Filters a redirect destination URL.
		 *
		 * @param string The URL to redirect to. An empty value will cancel the redirect.
		 */
		$destination_url = apply_filters( 'rollingstone_redirect_destination_url', $destination_url, $this->path );

		/**
		 * Filters the status of a redirect.
		 *
		 * @param int The redirect status. Must be either 301 or 302.
		 * @param string The redirect destination URL.
		 */
		$status = apply_filters( 'rollingstone_redirect_status', $redirect_status, $destination_url, $this->path );

		if ( ! empty( $destination_url ) && in_array( $status, [ 301, 302 ], true ) ) {
			wp_safe_redirect( $destination_url, $status );
		}

		exit;

	}

	/**
	 * Checks for whitelisted material at the end of a single post permalink and returns it if it exists.
	 *
	 * @param string $request_uri The URL path.
	 * @return string The endpoint string or empty string if none found.
	 */
	public function get_endpoint_string_from_request_uri( $request_uri ) {

		$endpoint_patterns = [
			'/(\/feed\/(feed|rdf|rss|rss2|atom)?\/?)$/',
			'/(\/(feed|rdf|rss|rss2|atom)\/?)$/',
			'/(\/embed\/?)$/',
			'/(\/trackback\/?)$/',
			'/(\/fbid(\/(.*))?\/?)$/',
			'/(\/amp(\/(.*))?\/?)$/',
			'/(\/page\/([0-9]{1,})\/?)$/',
		];

		foreach ( (array) $endpoint_patterns as $pattern ) {
			preg_match( $pattern, $request_uri, $matches );

			if ( isset( $matches[0] ) && ! empty( $matches[0] ) ) {
				return $matches[0];
			}

		}

		return '';
	}

	/**
	 * Breaks a WordPress URL path into the post slug, the endpoint slug, and the query.
	 *
	 * @param string $path A WordPress URL path.
	 * @return array The parsed URL parts.
	 */
	public function parse_wp_path( $path ) {

		$parts         = wp_parse_url( $path );
		$parts['path'] = strtolower( $parts['path'] );
		$endpoint      = PMC::unleadingslashit( $this->get_endpoint_string_from_request_uri( $parts['path'] ) );

		/*
		 * If endpoint is there then remove it from the path part as it will be used as
		 * slug which will be used to compare with legacy/canonical URIs for possible redirects.
		 */
		if ( ! empty( $endpoint ) ) {
			$endpoint_length = strlen( $endpoint );
			$parts['path']   = substr( $parts['path'], 0, ( 0 - abs( $endpoint_length ) ) );
		}

		return [
			'query'    => ( isset( $parts['query'] ) ) ? $parts['query'] : '',
			'endpoint' => $endpoint,
			'slug'     => $parts['path'],
		];

	}

	/**
	 * Since we don't do strict checking on categories and ID's in our post slug
	 * structure, we need to redirect if a user is visiting a URL that is not the
	 * exact permalink they should be viewing to avoid duplicate content.
	 */
	public function canonical_redirect() {

		/**
		 * This would not be required for pmc-gallery as we have custom rewrite rule for single gallery slug.
		 *
		 * * @todo array union here is incorrect, it was probably assumed that it will merge two arrays.
		 */
		if ( is_singular( [ 'post' ] + Rewrites::POST_TYPES ) && ! is_preview() && ! is_singular( 'pmc-gallery' ) ) {

			if ( empty( $this->path ) ) {
				return;
			}

			$path_parts         = $this->parse_wp_path( $this->path );
			$path_parts['slug'] = PMC::leadingslashit( trailingslashit( $path_parts['slug'] ) );
			$permalink          = get_permalink( get_the_ID() );

			if ( ! empty( $permalink ) ) {
				$permalink_slug = PMC::leadingslashit( trailingslashit( wp_parse_url( $permalink, PHP_URL_PATH ) ) );
			}

			if ( empty( $permalink_slug ) ) {
				return;
			}

			if ( $permalink_slug !== $path_parts['slug'] ) {

				$canonical_path = trailingslashit( $permalink ) . $path_parts['endpoint'];

				if ( ! empty( $path_parts['query'] ) ) {
					$canonical_path .= '?' . $path_parts['query'];
				}

				$this->redirect_to( $canonical_path, 301 );

			}

		}

	}

	/**
	 * This method is called by 'rollingstone_redirect_destination_url' filter and sets up
	 * one to one redirects for certain URIs in Glixel section instead of having them redirected
	 * to the default Glixel redirect destination.
	 *
	 * @param string $destination_url
	 * @param string $source_url
	 *
	 * @return string
	 */
	public function maybe_setup_glixel_one_to_one_redirects( $destination_url, $source_url ) {

		if (
			empty( $destination_url ) || ! is_string( $destination_url )
			|| empty( $source_url ) || ! is_string( $source_url )
		) {
			return $destination_url;
		}

		$uri = wp_parse_url( $source_url, PHP_URL_PATH );
		$uri = untrailingslashit( PMC::leadingslashit( $uri ) );

		if ( ! empty( $uri ) && ! empty( $this->_glixel_one_to_one_redirects[ $uri ] ) ) {
			$destination_url = $this->_glixel_one_to_one_redirects[ $uri ];
		}

		return $destination_url;

	}

}

//EOF
