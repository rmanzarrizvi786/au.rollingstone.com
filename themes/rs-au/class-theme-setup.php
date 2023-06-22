<?php
class ThemeSetup
{

	/**
	 * Class constructor.
	 *
	 * Initializes the theme assets.
	 */
	public function __construct()
	{

		// Register Menus
		add_action('init', [$this, 'register_nav_menus']);

		// Assets handling.
		add_action('wp_head', [$this, 'add_preload_polyfill'], 10);
		add_action('wp_enqueue_scripts', [$this, 'site_css']);
		add_action('wp_head', [$this, 'hint_web_fonts'], 1);
		add_action('wp_head', [$this, 'inline_web_fonts'], 2);
		add_action('wp_enqueue_scripts', [$this, 'dequeue_assets'], 11);
		add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts'], 11);

		// Disable JS & CSS concatenation as we're on HTTPS and want defers to work.
		add_filter('css_do_concat', '__return_false', 10, 2);
		add_filter('js_do_concat', '__return_false', 10, 2);

		// Lazyload and defer scripts and styles.
		add_filter('script_loader_tag', array($this, 'defer_scripts'), 999, 2);
		add_filter('script_loader_tag', array($this, 'async_scripts'), 999, 2);

		// Disable all actions related to emojis
		remove_action('admin_print_styles', 'print_emoji_styles');
		remove_action('wp_head', 'print_emoji_detection_script', 7);
		remove_action('admin_print_scripts', 'print_emoji_detection_script');
		remove_action('wp_print_styles', 'print_emoji_styles');
		remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
		remove_filter('the_content_feed', 'wp_staticize_emoji');
		remove_filter('comment_text_rss', 'wp_staticize_emoji');
		add_filter('emoji_svg_url', '__return_false');

		// Misc footer items.
		add_action('wp_footer', array($this, 'inline_icons'), 10);

		add_action('after_setup_theme', [$this, 'theme_setup']);
		add_action('widgets_init', [$this, 'register_sidebars']);

		// Infinite scroll
		add_action('wp_ajax_tbm_ajax_load_next_post', [$this, 'tbm_ajax_load_next_post']);
		add_action('wp_ajax_nopriv_tbm_ajax_load_next_post', [$this, 'tbm_ajax_load_next_post']);

		// Register Menus
		add_action('init', [$this, 'register_taxonomy']);

		// Register Featured Article Fields
		add_action('init', array($this, 'register_styled_heading'));

		// Shortcodes
		// YouTube Shortcode - to cover old site content
		add_shortcode('youtube', array($this, 'youtube_shortcode_func'));

		// Advert Shortcode - to cover old site content
		add_shortcode('advert', array($this, 'advert_shortcode_func'));

		// Custom Columns
		add_action("manage_posts_columns",  array($this, "custom_columns"));
		add_filter("manage_posts_custom_column", array($this, "edit_columns"), 10, 2);

		add_action('admin_init', function () {
			add_post_type_support('pmc_list_item', 'page-attributes');
		});

		// External URL as featured image, image URLs can be used from RS US site
		// add_filter( 'admin_post_thumbnail_html', array( $this, 'admin_post_thumbnail_html' ) );
		add_action('save_post', array($this, 'save_post'), 10, 2);
		add_filter('post_thumbnail_html', array($this, 'post_thumbnail_html'), 10, PHP_INT_MAX);

		// Custom OG Image
		// add_filter('wp_head', array($this, 'custom_opengraph_image_url'));
		add_filter('wpseo_opengraph_image', [$this, 'wpseo_opengraph_image']);

		// Ad in content
		add_filter('the_content', array($this, 'inject_ads'));

		// YouTube Lazy Load
		// add_filter('the_content', array( $this, 'youtube_lazy_load' ) );

		// FB Instant Articles RSS
		add_action('init', array($this, 'fbInstantArticleRSS'));

		// Filters for Apple News
		add_filter('get_the_author_display_name', array($this, 'tbm_the_author_display_name'));
		add_filter('apple_news_exporter_content_pre', array($this, 'apple_news_exporter_content_pre'), 10, 2);

		/*
		 * Change Default Email Address and From Name for the outgoing emails
		 */
		add_filter('wp_mail_content_type', function () {
			return "text/html";
		});
		add_filter('wp_mail_from', function () {
			return 'noreply@thebrag.media';
		});
		add_filter('wp_mail_from_name', function () {
			return 'Rolling Stone Australia';
		});
		add_action('phpmailer_init', array($this,  'phpmailer_init'));

		// add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		// Set cookie (AJAX)
		add_action('wp_ajax_tbm_ajax_set_cookie', [$this, 'tbm_ajax_set_cookie']);
		add_action('wp_ajax_nopriv_tbm_ajax_set_cookie', [$this, 'tbm_ajax_set_cookie']);

		add_action('wp_ajax_tbm_set_cookie', [$this, 'tbm_ajax_set_cookie']);
		add_action('wp_ajax_nopriv_tbm_set_cookie', [$this, 'tbm_ajax_set_cookie']);

		add_action('wp_ajax_tbm_ajax_get_cookie', [$this, 'tbm_ajax_get_cookie']);
		add_action('wp_ajax_nopriv_tbm_ajax_get_cookie', [$this, 'tbm_ajax_get_cookie']);

		// Exclude password protected posts from loop
		add_filter('posts_where', [$this, 'password_post_filter']);

		add_filter('the_excerpt_rss', [$this, 'post_thumbnails_in_feeds']);
		add_filter('the_content_feed', [$this, 'post_thumbnails_in_feeds']);

		add_action('init', function () {
			add_feed('flipboard', [$this, 'rss_flipboard_func']);
		});

		// add_action( 'wp_head', [ $this, 'inject_gotchosen' ] );

		add_filter('the_content', [$this, 'remove_p_tags_around_script']);
		add_filter('the_content', [$this, 'filter_ptags_on_images']);
		add_filter('the_content', [$this, 'remove_p_tags_around_iframes']);

		add_filter('the_content', [$this, 'links_autoblank']);

		// Ad in content
		add_shortcode('dailymotion_playlist', [$this, 'shortcode_dailymotion_playlist']);

		// Social Share Shortcode
		add_shortcode('tbm_social_share', [$this, 'shortcode_social_share']);

		add_filter('attachments_default_instance', '__return_false'); // disable the default instance
		add_action('attachments_register', [$this, 'gallery_attachments']);

		add_action('init', [$this, 'register_post_types']);

		// New Zealand custom post type, add custom field
		add_action('save_post_pmc-nz', [$this, 'save_pmc_nz'], 99, 2);
	}

	/**
	 * Function is used to register post `pmc-attachments`.
	 */
	public function register_post_types()
	{
		register_post_type(
			'pmc-gallery',
			array(
				'labels'        => array(
					'name'               => esc_html__('Galleries', 'pmc-gallery-v4'),
					'singular_name'      => esc_html__('Gallery', 'pmc-gallery-v4'),
					'add_new'            => esc_html__('Add New Gallery', 'pmc-gallery-v4'),
					'add_new_item'       => esc_html__('Add New Gallery', 'pmc-gallery-v4'),
					'edit'               => esc_html__('Edit Gallery', 'pmc-gallery-v4'),
					'edit_item'          => esc_html__('Edit Gallery', 'pmc-gallery-v4'),
					'new_item'           => esc_html__('New Gallery', 'pmc-gallery-v4'),
					'view'               => esc_html__('View Gallery', 'pmc-gallery-v4'),
					'view_item'          => esc_html__('View Gallery', 'pmc-gallery-v4'),
					'search_items'       => esc_html__('Search Galleries', 'pmc-gallery-v4'),
					'not_found'          => esc_html__('No Gallery found', 'pmc-gallery-v4'),
					'not_found_in_trash' => esc_html__('No Gallery found in Trash', 'pmc-gallery-v4'),
				),
				'public'        => true,
				'menu_position' => 5,
				'supports'      => array(
					'title',
					'editor',
					'author',
					'excerpt',
					'comments',
					'thumbnail',
					'custom-fields',
					'trackbacks',
					'revisions',
				),
				'taxonomies'    => array('category', 'post_tag'),
				// 'menu_icon'     => self::$url . 'assets/build/images/icon.png',
				'has_archive'   => true,
				'rewrite'       => array(
					'slug' => apply_filters('pmc_gallery_standalone_slug', 'gallery'),
				),
			)
		);

		register_post_type(
			'pmc-nz',
			array(
				'labels'        => array(
					'name'               => esc_html__('NZ Articles', 'pmc-nz'),
					'singular_name'      => esc_html__('NZ Article', 'pmc-nz'),
					'add_new'            => esc_html__('Add New NZ Article', 'pmc-nz'),
					'add_new_item'       => esc_html__('Add New NZ Article', 'pmc-nz'),
					'edit'               => esc_html__('Edit NZ Article', 'pmc-nz'),
					'edit_item'          => esc_html__('Edit NZ Article', 'pmc-nz'),
					'new_item'           => esc_html__('New NZ Article', 'pmc-nz'),
					'view'               => esc_html__('View NZ Article', 'pmc-nz'),
					'view_item'          => esc_html__('View NZ Article', 'pmc-nz'),
					'search_items'       => esc_html__('Search NZ Articles', 'pmc-nz'),
					'not_found'          => esc_html__('No NZ Article found', 'pmc-nz'),
					'not_found_in_trash' => esc_html__('No NZ Article found in Trash', 'pmc-nz'),
				),
				'public'        => true,
				'menu_position' => 5,
				'supports'      => array(
					'title',
					'editor',
					'author',
					'excerpt',
					'comments',
					'thumbnail',
					'custom-fields',
					'trackbacks',
					'revisions',
				),
				'taxonomies'    => array('category', 'post_tag'),
				// 'menu_icon'     => self::$url . 'assets/build/images/icon.png',
				'has_archive'   => true,
				'rewrite'       => array(
					'slug' => 'nz', // apply_filters('pmc_gallery_standalone_slug', 'gallery'),
				),
			)
		);

		/* register_post_type('pmc-attachments', [
			'labels'        => [
				'name'               => _x('Gallery Attachment', 'post type general name', 'pmc-gallery-v4'),
				'singular_name'      => _x('Gallery Attachment', 'post type singular name', 'pmc-gallery-v4'),
				'add_new'            => _x('Add New', 'Gallery Attachment', 'pmc-gallery-v4'),
				'add_new_item'       => __('Add New Gallery Attachment', 'pmc-gallery-v4'),
				'edit_item'          => __('Edit Gallery Attachment', 'pmc-gallery-v4'),
				'new_item'           => __('New Gallery Attachment', 'pmc-gallery-v4'),
				'all_items'          => __('All Gallery Attachments', 'pmc-gallery-v4'),
				'view_item'          => __('View Gallery Attachment', 'pmc-gallery-v4'),
				'search_items'       => __('Search Gallery Attachment', 'pmc-gallery-v4'),
				'not_found'          => __('No Gallery Attachment found', 'pmc-gallery-v4'),
				'not_found_in_trash' => __('No Gallery Attachment found in the Trash', 'pmc-gallery-v4'),
				'parent_item_colon'  => '',
				'menu_name'          => __('Gallery Attachment', 'pmc-gallery-v4')
			],
			'description'   => __('Holds gallery specific attachment\'s data', 'pmc-gallery-v4'),
			'menu_position' => 5,
			'supports'      => array('title', 'editor'),
			'public'        => false,
			'show_ui'       => false,
		]); */
	}

	public function save_pmc_nz($post_id, $post)
	{
		update_post_meta($post_id, 'add_to_nz_content', 1);
	} // save_pmc_nz()


	public function gallery_attachments($attachments)
	{
		$fields = array(
			array(
				'name'      => 'title',
				'type'      => 'text',
				'label'     => __('Title', 'photos'),
				'default'   => 'title',
			),
			array(
				'name'      => 'date',
				'type'      => 'text',
				'label'     => __('Date', 'photos'),
				'default'   => 'date',
			),
			array(
				'name'      => 'content',
				'type'      => 'textarea',
				'label'     => __('Content', 'photos'),
				'default'   => 'content',
			),
			array(
				'name'      => 'credit',
				'type'      => 'text',
				'label'     => __('Credit', 'photos'),
				'default'   => 'credit',
			),
		);
		$args = array(
			'label'         => 'Gallery', // title of the meta box (string)
			'post_type'     => array('pmc-gallery'), // all post types to utilize (string|array)
			'position'      => 'normal', // meta box position (string) (normal, side or advanced)
			'priority'      => 'high', // meta box priority (string) (high, default, low, core)
			'filetype'      => null,  // no filetype limit // allowed file type(s) (array) (image|video|text|audio|application)
			'note'          => 'Attach images here!', // include a note within the meta box (string)
			'append'        => true, // by default new Attachments will be appended to the list but you can have then prepend if you set this to false
			'button_text'   => __('Attach Images', 'images'), // text for 'Attach' button in meta box (string)
			'modal_text'    => __('Attach', 'images'), // text for modal 'Attach' button (string)
			'router'        => 'browse', // which tab should be the default in the modal (string) (browse|upload)
			'post_parent'   => false, // whether Attachments should set 'Uploaded to' (if not already set)
			'fields'        => $fields, // fields array
		);

		$attachments->register('gallery_attachments', $args); // unique instance name
	}

	public function rss_flipboard_func()
	{
		get_template_part('rss', 'flipboard');
	}

	public function phpmailer_init($phpmailer)
	{
		$phpmailer->isSMTP();
		$phpmailer->Host       = 'smtp.gmail.com';
		$phpmailer->SMTPAuth   = true;
		$phpmailer->Port       = 587;
		$phpmailer->Username   = 'noreply@thebrag.media';
		$phpmailer->Password   = '<%QA5hXy1';
		$phpmailer->SMTPSecure = 'tls';
		$phpmailer->From       = 'noreply@thebrag.media';
		$phpmailer->FromName   = 'Rolling Stone Australia';

		$phpmailer->IsSMTP();
	}

	public function tbm_the_author_display_name()
	{
		global $post;
		if (get_field('author') && '' != trim(get_field('author'))) {
			return get_field('author');
		}
		$author_id = $post->post_author;
		return get_the_author_meta('first_name', $author_id) . ' ' . get_the_author_meta('last_name', $author_id);
	}

	public function apple_news_exporter_content_pre($content, $postID)
	{

		global $post;

		$content = preg_replace("/<script.*?\/script>/s", '', $content) ?: $content;
		$content = preg_replace("/<script.*?\/>/s", '', $content) ?: $content;
		$content = preg_replace("/<h3>(.*?)Popular on Rolling Stone(.*?)\/h3>/s", '', $content) ?: $content;
		$content = str_replace('<div id="jwplayer_contextual_player_div"/>', '', $content);

		$content = str_replace(
			array(
				"<![CDATA[ /**/ ]]>",
				"&lt;![CDATA[\n<p>]]&gt",
				"<![CDATA[\n<p>]]>"
			),
			array(""),
			$content
		);

		$video_source = get_post_meta($post->ID, 'pmc_top_video_source', true);

		// print_r( ( $content ) ) ; exit;

		if ($video_source) {
			return '<p>' . $video_source . '</p>' . $content;
		} else {
			return $content;
		}
	}

	public function admin_post_thumbnail_html($html)
	{
		global $post;
		$value = get_post_meta($post->ID, '_thumbnail_ext_url', TRUE) ?: "";
		$nonce = wp_create_nonce('thumbnail_ext_url_' . $post->ID . get_current_blog_id());
		$html .= '<input type="hidden" name="thumbnail_ext_url_nonce" value="' . esc_attr($nonce) . '">';
		$html .= '<div><p>' . __('Or', 'www-post-thumb') . '</p>';
		$html .= '<p>' . __('Enter the url for external featured image', 'www-post-thumb') . '</p>';
		$html .= '<p><input type="url" name="thumbnail_ext_url" value="' . $value . '"></p>';
		if (!empty($value) && $this->is_image($value)) {
			$html .= '<p><img style="max-width:150px;height:auto;" src="' . esc_url($value) . '"></p>';
			$html .= '<p>' . __('Leave url blank to remove.', 'www-post-thumb') . '</p>';
		}
		$html .= '</div>';
		return $html;
	}

	public function save_post($pid, $post)
	{
		$cap = $post->post_type === 'page' ? 'edit_page' : 'edit_post';
		if (
			!current_user_can($cap, $pid)
			|| !post_type_supports($post->post_type, 'thumbnail')
			|| defined('DOING_AUTOSAVE')
		) {
			return;
		}
		$action = 'thumbnail_ext_url_' . $pid . get_current_blog_id();
		$nonce = filter_input(INPUT_POST,  'thumbnail_ext_url_nonce', FILTER_SANITIZE_STRING);
		$url = filter_input(INPUT_POST,  'thumbnail_ext_url', FILTER_VALIDATE_URL);
		if (
			empty($nonce)
			|| !wp_verify_nonce($nonce, $action)
			|| (!empty($url) && !$this->is_image($url))
		) {
			return;
		}
		if (!empty($url)) {
			update_post_meta($pid, '_thumbnail_ext_url', esc_url($url));
			if (!get_post_meta($pid, '_thumbnail_id', TRUE)) {
				update_post_meta($pid, '_thumbnail_id', 'by_url');
			}
		} elseif (get_post_meta($pid, '_thumbnail_ext_url', TRUE)) {
			delete_post_meta($pid, '_thumbnail_ext_url');
			if (get_post_meta($pid, '_thumbnail_id', TRUE) === 'by_url') {
				delete_post_meta($pid, '_thumbnail_id');
			}
		}
	}

	function is_image($url)
	{
		$ext = array('jpeg', 'jpg', 'gif', 'png');
		$info = (array) pathinfo(parse_url($url, PHP_URL_PATH));
		return isset($info['extension']) && in_array(strtolower($info['extension']), $ext, TRUE);
	}

	public function post_thumbnail_html($html, $post_id)
	{
		$url =  get_post_meta($post_id, '_thumbnail_ext_url', TRUE);
		if (empty($url) || !$this->is_image($url)) {
			return $html;
		}
		$alt = get_post_field('post_title', $post_id) . ' ' .  __('thumbnail', 'www-post-thumb');
		$attr = array('alt' => $alt);
		$attr = apply_filters('wp_get_attachment_image_attributes', $attr, NULL);
		$attr = array_map('esc_attr', $attr);
		$html = sprintf('<img src="%s"', esc_url($url));
		foreach ($attr as $name => $value) {
			$html .= " $name=" . '"' . $value . '"';
		}
		$html .= ' />';
		return $html;
	}



	/**
	 * Dequeue Parent Theme Assets
	 *
	 * @since 2018.1.0
	 */
	public function dequeue_assets()
	{

		if (!is_single()) {
			// MediaElement Player is not needed outside of the single article.
			wp_deregister_style('mediaelement');
			wp_deregister_style('wp-mediaelement');
			wp_deregister_script('wp-mediaelement');

			// Social share bar is not present outside of the single article.
			wp_dequeue_style('pmc-social-share-bar-common-css');
		}

		//The JWPlayer plugins JS is conflicting with pmc-floating-player.
		// if ( ! PMC::is_mobile() && is_single() ) {
		// 	wp_dequeue_script( 'pmc-video-player-library' );
		// 	wp_dequeue_script( 'pmc-video-player-script' );
		// 	wp_dequeue_style( 'jetpack-css' );
		// }

		foreach (array('homepage', 'archive', 'site') as $base) {
			wp_dequeue_style('pmc-core-' . $base . '-css');
		}

		wp_dequeue_style('pmc-core-woff-webfonts-css');
		wp_dequeue_style('pmc-core-ttf-webfonts-css');
		wp_dequeue_style('pmc-global-css-overrides');
		wp_dequeue_style('pmcfooter');

		if (
			is_page()
			&& class_exists('\PMC\Top_Videos_V2\Landing_Pages\Branded_Page', false)
			&& \PMC\Top_Videos_V2\Landing_Pages\Branded_Page::get_instance()->is_enabled_for_post(get_the_ID())
		) {
			// Dequeue these scripts to prevent pre-roll ads on JW Player on branded landing page
			wp_dequeue_script('pmc-video-player-library');
			wp_dequeue_script('pmc-video-player-script');
		}

		if (function_exists('is_amp_endpoint') && is_amp_endpoint()) {
			wp_deregister_style('dashicons');
			wp_deregister_style('admin-bar');
			wp_deregister_style('pmc-helpdesk');
			wp_deregister_style('pmc-global-css-overrides');
			wp_deregister_style('pmcfooter');
			wp_deregister_style('pmc-social-share-bar-common-css');
			wp_deregister_style('pmc-swiftype-style');
			wp_deregister_style('pmc-adm-style');
		}

		wp_dequeue_script('wp-embed');
		wp_dequeue_script('pmc-core-site-js');
	}
	
	/**
	 * Enqueue Child Theme Assets
	 *
	 * @since 2018.1.0
	 */
	public function enqueue_scripts()
	{

		wp_register_script('pmc-hooks', TBM_CDN . '/assets/js/pmc/pmc-hooks.js', array(), RS_THEME_VERSION, false);

		// Main script.
		wp_register_script('rollingstone-main', TBM_CDN . '/assets/js/main.js', array('jquery', 'iolazy-js', 'polyfill-io-js'), RS_THEME_VERSION, false);

		wp_register_script('tbm', TBM_CDN . '/assets/js/tbm.min.js', array('jquery'), '20220121.1', false);
		// wp_register_script( 'tbm', TBM_CDN . '/assets/js/tbm.js', array( 'jquery' ), time(), false );

		$admin_ajax_url = admin_url('admin-ajax.php');
		if (is_single()) {
			global $post;
			$args = array(
				'url'   => $admin_ajax_url,
				'exclude_posts' => isset($post) ? $post->ID : NULL,
				'current_post' => isset($post) ? $post->ID : NULL
			);
			wp_localize_script('tbm', 'tbm_load_next_post', $args);
		}

		// Register IO Lazy used in images lazy loading.
		wp_register_script('polyfill-io-js', 'https://cdn.polyfill.io/v2/polyfill.min.js?features=IntersectionObserver,Promise,Fetch,Array.from', array(), null, true);
		wp_register_script('iolazy-js', TBM_CDN . '/assets/js/vendor/iolazy.js', array('polyfill-io-js'), null, true);

		// Gallery bundle.
		// wp_register_script( 'pmc-core-images-loaded', TBM_CDN . '/assets/js/vendor/imagesloaded.pkgd.js', array(), RS_THEME_VERSION, false );

		wp_deregister_script('pmc-core-gallery-bundle');

		// wp_register_style( 'gallery-styles', TBM_CDN . '/assets/css/gallery.css', [], RS_THEME_VERSION, 'all' );

		// Enqueue scripts and stylesheets.
		wp_enqueue_script('jquery');

		// if ( Featured_Article::get_instance()->is_featured_article() ) {
		if (has_term('featured-article', 'global-options')) {
			wp_enqueue_script('featured-article');
		}

		wp_enqueue_script('pmc-hooks');
		wp_enqueue_script('rollingstone-main');
		wp_enqueue_script('tbm');

		// Ranker ROIQ Test Script.
		/*
		wp_enqueue_script(
			'rollingstone-ranker-roiq',
			'https://cdn.roiq.ranker.com/client/assets/minified/roiq_dfp_targeting.js',
			array(),
			false,
			true
		);

		if ( is_singular( 'pmc-gallery' ) ) {
			wp_enqueue_style( 'gallery-styles' );
			wp_enqueue_script( 'waypoints' );
		}
		*/

		// wp_enqueue_script( 'rollingstone-newsletter-acquisition', 'https://www.lightboxcdn.com/vendor/f611bb46-e8d6-4b65-af04-d7801b2011c2/lightbox_inline.js', [], RS_THEME_VERSION, false );

		$data = [
			'ticketing' => [
				'api_endpoint'               => home_url('/api/seats/'),
				'no_location_text'           => esc_html__('Choose a Location', 'pmc-rollingstone'),
				'service_not_available_text' => esc_html__('Sorry, Service Not Available.', 'pmc-rollingstone'),
			],
		];

		wp_localize_script('rollingstone-main', 'RS_MAIN', $data);
	}

	/**
	 * Inline critical CSS in the page head.
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function site_css()
	{
		if (is_home() || is_front_page() || is_page_template('page-templates/page-nz.php')) {
			$css_slug = 'home';
			// } elseif ( is_page( 'video' ) ) {
		} elseif (is_page('video') || is_post_type_archive('pmc_top_video')) {
			$css_slug = 'video-landing';
		} elseif (
			is_page()
			&& class_exists('\PMC\Top_Videos_V2\Landing_Pages\Branded_Page', false)
			&& \PMC\Top_Videos_V2\Landing_Pages\Branded_Page::get_instance()->is_enabled_for_post(get_the_ID())
		) {
			$css_slug = 'video-landing';
			// } elseif ( Featured_Article::get_instance()->is_featured_article() ) {
		} elseif (is_singular() && has_term('featured-article', 'global-options')) {
			$css_slug = 'featured-article';
		} elseif (is_page('results')) {
			$css_slug = 'results';
			// } elseif ( is_singular( 'pmc_top_video' ) ) {
		} elseif (is_singular('pmc_top_video')) {
			$css_slug = 'video-article';
		} elseif (
			is_singular(['pmc_list', 'pmc_list_item']) ||
			is_page_template('page-templates/page-50-greatest-artists-2021.php')
			||
			is_page_template('page-templates/page-200-greatest-artists-2021.php')
		) {
			$css_slug = 'list';
		} elseif (rollingstone_is_country()) {
			$css_slug = 'country';
		} elseif (is_singular() && !is_page_template('page-templates/page-premium.php')) {
			$css_slug = 'single';
		} elseif (is_tax('vcategory')) {
			$css_slug = 'video-tag';
		} elseif (is_category()) {
			$category = get_query_var('cat');
			$category = get_category($category);
			if (!empty($category->parent)) {
				$css_slug = 'archive';
			} else {
				$css_slug = 'section-front';
			}
		} elseif (is_archive() || is_search() || is_page_template('page-templates/page-premium.php')) {
			$css_slug = 'archive';
		} else {
			$css_slug = 'main';
		}

		wp_enqueue_style($css_slug, TBM_CDN . '/assets/css/' . $css_slug . '.css', [], RS_THEME_VERSION, 'all');
		if (is_page_template('page-templates/page-nz.php')) {
			wp_enqueue_style('archive', TBM_CDN . '/assets/css/archive.css', [], RS_THEME_VERSION, 'all');
		}

		if (is_search()) {
			wp_enqueue_style('search', TBM_CDN . '/assets/css/results.css', [], RS_THEME_VERSION, 'all');
		}

		if (is_singular('pmc-gallery')) {
			wp_enqueue_style('gallery', TBM_CDN . '/assets/css/gallery.css', [], '20210813.1', 'all');
		}

		// Include TBM css in the end to overwrite
		wp_enqueue_style('tbm', TBM_CDN . '/assets/css/tbm.css', [], '20220725', 'all');
	}

	/**
	 * Add `rel="preload"` polyfill.
	 *
	 * This is part of Google's recommendation for lazy loading of style sheets.
	 *
	 * @since 2018.1.0
	 *
	 * @see https://github.com/filamentgroup/loadCSS/blob/master/src/cssrelpreload.js
	 */
	public function add_preload_polyfill()
	{
?>
		<script>
			<?php
			// Note that the non-minified resource is available for code review in assets/src/js/vendor/cssrelpreload.js
			\PMC::render_template(CHILD_THEME_PATH . '/assets/build/js/vendor/cssrelpreload.js', [], true);
			?>
		</script>
	<?php
	}

	/**
	 * Defer scripts loading.
	 *
	 * Adds defer='defer' attribute to non-whitelisted script tags.
	 *
	 * @param string $tag The tag.
	 * @param string $handle The handle.
	 * @since 2018.1.0
	 * @return string
	 */
	public function defer_scripts($tag, $handle)
	{

		if (!is_admin() && !(function_exists('is_amp_endpoint') && is_amp_endpoint())) {

			// Whitelist scripts that should not be deferred.
			$render_blocking_scripts = array(
				'jquery',
				'jquery-core',
				'jquery-migrate',
				'pmc-adm-loader',
				'pmc-adm-dfp-events',
				'pmc-adm-sourcebuster',
				'pmc-hooks',
				'pmc-intersection-observer-polyfill',
				'pmc-video-player-ads-js',
				'waypoints',
				'pmc-async-adm-gpt',
				'adm-fuse',
			);

			// The admin-bar has inline scripts depending on jQuery.
			if (is_user_logged_in()) {
				array_push(
					$render_blocking_scripts,
					'jquery',
					'jquery-core',
					'jquery-migrate'
				);
			}

			if (!in_array($handle, (array) $render_blocking_scripts, true)) {
				return str_replace(array(' src', ' async'), array(" defer='defer' src", ''), $tag);
			}
		}

		return $tag;
	}

	/**
	 * Adds async attribute to certain script tags.
	 *
	 * @param string $tag The tag.
	 * @param string $handle The handle.
	 * @since 2018.1.0
	 * @return string
	 */
	public function async_scripts($tag, $handle)
	{

		if (!is_admin() && !(function_exists('is_amp_endpoint') && is_amp_endpoint())) {
			$scripts_to_async = array(
				'mobile-useragent-info',
			);

			if (in_array($handle, (array) $scripts_to_async, true)) {
				return str_replace(' src', ' async src', $tag);
			}
		}

		return $tag;
	}

	/**
	 * Inline social icons.
	 *
	 * @since 2018.1.0
	 */
	public function inline_icons()
	{
		/*
		 * We're loading SVG assets from `src` here because webpack is stripping data from
		 * the SVG files, which in turn is causing them not to load here and the images to
		 * break on the frontend.
		 *
		 * @todo: Investigate why and change to the `build` directory when working.
		 */
	?>
		<div hidden>
			<?php
			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/icon-arrow-down.svg', [], true);
			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/icon-camera.svg', [], true);
			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/icon-chevron.svg', [], true);
			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/icon-comments.svg', [], true);
			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/icon-email.svg', [], true);
			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/icon-external-link.svg', [], true);
			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/icon-facebook.svg', [], true);
			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/icon-hamburger.svg', [], true);
			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/icon-linkedin.svg', [], true);
			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/icon-key.svg', [], true);
			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/icon-list.svg', [], true);
			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/icon-more.svg', [], true);
			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/icon-pinterest.svg', [], true);
			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/icon-play.svg', [], true);
			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/icon-plus.svg', [], true);
			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/icon-print.svg', [], true);
			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/icon-reddit.svg', [], true);
			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/icon-share.svg', [], true);
			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/icon-star.svg', [], true);
			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/icon-star--half.svg', [], true);
			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/icon-thumbnails.svg', [], true);
			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/icon-trending.svg', [], true);
			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/icon-tumblr.svg', [], true);
			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/icon-twitter.svg', [], true);
			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/icon-whatsapp.svg', [], true);
			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/icon-youtube.svg', [], true);
			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/pmc-logo-black.svg', [], true);
			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/pmc-logo-white.svg', [], true);
			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/rs-badge.svg', [], true);

			\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/rs-logo.svg', [], true);

			// theses were commented out {
			// \PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/RS-AU_LOGO-RED_RS_LOGO-RED.svg', [], true );
			// \PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/RS-AU_LOGO-RED.png', [], true );
			// \PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/logo-rspro.svg', [], true );
			// \PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/logo-rs-live-media.svg', [], true );
			// } theses were commented out

			//\PMC::render_template(CHILD_THEME_PATH . '/assets/src/images/_dev/icon-instagram.svg', [], true);
			?>
		</div>
	<?php
	}

	/**
	 * Inline custom web fonts.
	 *
	 * @since 2018.1.0
	 */
	public function inline_web_fonts()
	{
		$fonts_url = RS_THEME_URL . '/assets/build/fonts';
	?>
		<style type="text/css" id="web-fonts-css">
			@font-face {
				font-family: 'Graphik Bold Subset';
				src: url('<?php echo esc_url($fonts_url); ?>/Graphik/Graphik-Bold-Subset.woff2') format('woff2'),
					url('<?php echo esc_url($fonts_url); ?>/Graphik/Graphik-Bold-Subset.woff') format('woff');
				font-weight: 700;
				font-style: normal;
				font-display: swap;
			}

			@font-face {
				font-family: 'Graphik';
				src: url('<?php echo esc_url($fonts_url); ?>/Graphik/Graphik-Regular.woff2') format('woff2'),
					url('<?php echo esc_url($fonts_url); ?>/Graphik/Graphik-Regular.woff') format('woff');
				font-weight: 300;
				font-style: normal;
				font-display: swap;
			}

			@font-face {
				font-family: 'Graphik';
				src: url('<?php echo esc_url($fonts_url); ?>/Graphik/Graphik-Medium.woff2') format('woff2'),
					url('<?php echo esc_url($fonts_url); ?>/Graphik/Graphik-Medium.woff') format('woff');
				font-weight: 500;
				font-style: normal;
				font-display: swap;
			}

			@font-face {
				font-family: 'Graphik';
				src: url('<?php echo esc_url($fonts_url); ?>/Graphik/Graphik-Semibold.woff2') format('woff2'),
					url('<?php echo esc_url($fonts_url); ?>/Graphik/Graphik-Semibold.woff') format('woff');
				font-weight: 600;
				font-style: normal;
				font-display: swap;
			}

			@font-face {
				font-family: 'Graphik';
				src: url('<?php echo esc_url($fonts_url); ?>/Graphik/Graphik-Bold.woff2') format('woff2'),
					url('<?php echo esc_url($fonts_url); ?>/Graphik/Graphik-Bold.woff') format('woff');
				font-weight: 700;
				font-style: normal;
				font-display: swap;
			}

			@font-face {
				font-family: 'Graphik Super';
				src: url('<?php echo esc_url($fonts_url); ?>/Graphik/Graphik-Super.woff2') format('woff2'),
					url('<?php echo esc_url($fonts_url); ?>/Graphik/Graphik-Super.woff') format('woff');
				font-weight: 800;
				font-style: normal;
				font-display: swap;
			}

			@font-face {
				font-family: 'Publico Bold Subset';
				src: url('<?php echo esc_url($fonts_url); ?>/Publico/Publico-Bold-Subset.woff2') format('woff2'),
					url('<?php echo esc_url($fonts_url); ?>/Publico/Publico-Bold-Subset.woff') format('woff');
				font-weight: 700;
				font-style: normal;
				font-display: swap;
			}

			@font-face {
				font-family: 'Publico';
				src: url('<?php echo esc_url($fonts_url); ?>/Publico/Publico-Bold.woff2') format('woff2'),
					url('<?php echo esc_url($fonts_url); ?>/Publico/Publico-Bold.woff') format('woff');
				font-weight: 700;
				font-style: normal;
				font-display: swap;
			}
		</style>
		<?php
	}

	/**
	 * Hints for custom web fonts.
	 *
	 * @since 2018.1.0
	 */
	public function hint_web_fonts()
	{
		$fonts_url = RS_THEME_URL . '/assets/build/fonts';

		$fonts = [
			'/Graphik/Graphik-Regular',
			'/Graphik/Graphik-Medium',
			'/Graphik/Graphik-Semibold',
			'/Graphik/Graphik-Bold',
			'/Graphik/Graphik-Bold-Subset',
			'/Graphik/Graphik-Super',
			'/Publico/Publico-Bold',
			'/Publico/Publico-Bold-Subset',
		];

		foreach ($fonts as $font) {
		?>
			<link rel="preload" href="<?php echo esc_url($fonts_url); ?><?php echo esc_attr($font); ?>.woff2" as="font" type="font/woff2" crossorigin="anonymous">
			<link rel="preload" href="<?php echo esc_url($fonts_url); ?><?php echo esc_attr($font); ?>.woff" as="font" type="font/woff2" crossorigin="anonymous">
		<?php
		}
	}

	/**
	 * Register nav menus for child theme.
	 *
	 * @since 2018-04-13
	 */
	public function register_nav_menus()
	{
		$menus = [
			// PMC Core
			'pmc_core_header'      => __('Header', 'pmc-core'),
			'pmc_core_mega'        => __('Mega - Main', 'pmc-core'),
			'pmc_core_mega_bottom' => __('Mega - Bottom', 'pmc-core'),
			'pmc_core_trending'    => __('Trending News - Home Page', 'pmc-core'),
			'pmc_core_footer'      => __('Footer', 'pmc-core'),
			'pmc_core_social'      => __('Social', 'pmc-core'),
			'pmc_core_exclusive'   => __('Top Bar Exclusive', 'pmc-core'),
			// Rs
			'rollingstone_videos'                  => __('Videos', 'pmc-rollingstone'),
			'rollingstone_footer_legal'            => __('Footer - Legal', 'pmc-rollingstone'),
			'rollingstone_footer_get_the_magazine' => __('Footer - Get The Magazine', 'pmc-rollingstone'),
		];

		// Create menu's for each primary category.
		$categories = get_terms(
			array(
				'taxonomy' => 'category',
			)
		);

		// Only parent categories.
		foreach ($categories as $category) {
			if (0 === $category->parent) {
				$menus[$category->slug . '-category-menu'] = $category->name . ' ' . __('Category Menu', 'pmc-rollingstone');
			}
		}

		register_nav_menus($menus);
	}

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which runs
	 * before the init hook. The init hook is too late for some features, such as
	 * indicating support post thumbnails.
	 */
	public function theme_setup()
	{
		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support('post-thumbnails');
		add_theme_support('title-tag');
	}

	public function register_sidebars()
	{

		register_sidebar(
			array(
				'name'          => 'Home right sidebar',
				'id'            => 'home_right_1',
				'before_widget' => '<div>',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="rounded">',
				'after_title'   => '</h2>',
			)
		);

		register_sidebar(
			array(
				'name'          => 'Archive right sidebar',
				'id'            => 'archive_right_1',
				'before_widget' => '<div>',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="rounded">',
				'after_title'   => '</h2>',
			)
		);

		register_sidebar(
			array(
				'name'          => __('Gallery Right Sidebar', 'pmc'),
				'id'            => 'gallery-right',
				'before_widget' => false,
				'after_widget'  => false,
				'before_title'  => false,
				'after_title'   => false,
			)
		);

		register_sidebar(
			array(
				'name'          => __('Article Right Sidebar', 'pmc'),
				'id'            => 'article_right_sidebar',
				'before_widget' => false,
				'after_widget'  => false,
				'before_title'  => false,
				'after_title'   => false,
			)
		);
	}

	/**
	 * @since 2017-07-31 Amit Sannad
	 *        Get breadcrumb for templates to display.
	 * @return array
	 */
	public static function get_breadcrumb()
	{

		$breadcrumb = [];

		// single posts
		$post_id = get_queried_object_id();

		$PMC_Primary_Taxonomy = new PMC_Primary_Taxonomy();
		// $vertical = \PMC_Primary_Taxonomy::get_instance()->get_primary_taxonomy( $post_id, 'vertical' );
		$vertical = $PMC_Primary_Taxonomy->get_primary_taxonomy($post_id, 'vertical');

		if (!empty($vertical->name)) {
			$breadcrumb[] = $vertical;
		}

		$category_id = apply_filters('breadcrumb_primary_category_id', get_post_meta($post_id, 'categories', true));

		if (!empty($category_id)) {
			$category = get_term_by('id', $category_id, 'category');
		} else {
			$category = $PMC_Primary_Taxonomy->get_primary_taxonomy($post_id, 'category');
		}

		if (!empty($category->parent)) {
			$parent_category = get_term_by('id', $category->parent, 'category');
			if (!empty($parent_category->name)) {
				$breadcrumb[] = $parent_category;
			}
		}

		if (!empty($category->name)) {
			$breadcrumb[] = $category;
		}

		$sub_category = '';
		$sub_cat_id   = apply_filters('breadcrumb_secondary_category_id', get_post_meta($post_id, 'subcategories', true));

		if (!empty($sub_cat_id)) {
			$sub_category = get_term_by('id', $sub_cat_id, 'category');
		}

		if (!empty($sub_category->name) && $category->name !== $sub_category->name) {
			$breadcrumb[] = $sub_category;
		}

		if (is_page()) {
			$post_id   = get_queried_object_id();
			$ancestors = get_post_ancestors($post_id);

			if (is_array($ancestors)) {
				$ancestors = array_reverse($ancestors);
			} else {
				$ancestors = [];
			}

			$ancestors[] = $post_id;

			foreach ($ancestors as $ancestor) {
				$item         = [];
				$item['name'] = get_the_title($ancestor);
				$item['link'] = get_permalink($ancestor);

				$breadcrumb[] = (object) $item;
			}
		}

		return $breadcrumb;
	}

	public static function get_authors($post_id = null, $override = false)
	{
		$authors = [];

		if (function_exists('get_coauthors')) {
			$authors = get_coauthors($post_id);
		}

		if (empty($authors)) {
			return;
		}

		if (true === $override) {
			$authors_or = [];
			$authors_or['escaped_html'] = coauthors_posts_links(null, null, null, null, false);
			$authors_or['authors'] = $authors;

			$size = \PMC::is_mobile() ? 50 : 64;
			if (!empty($authors[0])) {
				$authors_or['gravatar'] = coauthors_get_avatar($authors[0], $size, 'blank', false);
			}
			return $authors_or;
		}

		return apply_filters(self::FILTER_COAUTHORS, $authors);
	}

	/**
	 * Get Random Recent Post
	 *
	 * Get a single random recent post (respects the current vertical. Derived from
	 * `pmc_core_get_the_latest_posts_query`.
	 *
	 * @see   pmc_core_get_the_latest_posts_query
	 * @uses  pmc_core_article_post_types
	 * @uses  pmc_get_the_primary_term
	 *
	 * @version 2018-03-21 brandoncamenisch - feature/WI-498:
	 * - Removing query assignment on if statement while checking cache_key trans
	 *
	 * @since 2017.1.0
	 * @return  mixed/array
	 */
	public static function get_random_recent_post()
	{
		if (is_singular()) {
			$post_id = get_the_ID();
		}

		if (!empty($post_id)) {
			// Remove deprecated function.
			$PMC_Primary_Taxonomy = new PMC_Primary_Taxonomy();
			// $vertical = \PMC_Primary_Taxonomy::get_instance()->get_primary_taxonomy( $post_id, 'vertical' );
			$vertical = $PMC_Primary_Taxonomy->get_primary_taxonomy($post_id, 'category');
		} elseif (is_tax('vertical')) {
			$vertical = get_queried_object();
		}

		if (empty($vertical)) {
			$cache_key = 'vertical-latest-posts-home';
		} else {
			$cache_key = 'vertical-latest-posts-' . $vertical->term_id;
		}

		$query = get_transient($cache_key);

		if (false === $query) {
			$args = array(
				'post_type'      => ['post', 'pmc-gallery'],
				'posts_per_page' => 10,
			);

			if (!empty($vertical)) {

				$args['tax_query'] = array( //@codingStandardsIgnoreLine: Usage of tax_query is required as we need post with vertical taxonomy.
					array(
						'taxonomy'         => 'category',
						'terms'            => array($vertical->term_id),
						'include_children' => false,
					),
				);
			}

			$args = apply_filters('pmc_core_random_recent_post_args', $args);

			$query = new \WP_Query($args);

			set_transient($cache_key, $query, 15 * MINUTE_IN_SECONDS);
		}

		// Return a random post.
		if (!empty($query->posts) && is_array($query->posts)) {
			return $query->posts[mt_rand(0, count($query->posts) - 1)];
		}

		return false;
	}

	public static function render_ads($tag, $device = '', $ad_width = '', $slot_no = 1)
	{
		if (get_field('disable_ads')) :
			return;
		endif;

		if (is_singular('pmc-gallery'))
			return;

		// if (file_exists(WP_PLUGIN_DIR . '/tbm-adm/tbm-adm.php')) 
		{
			// require_once WP_PLUGIN_DIR . '/tbm-adm/tbm-adm.php';
			$ads = TBMAds::get_instance();
			echo $ads->get_ad($tag, $slot_no, get_the_ID());
			return;
		}
		?>
		<div class="admz" id="adm-<?php echo esc_attr($ad_location . $slot_no); ?>">
			<div class="adma" data-device="<?php echo esc_attr($device); ?>" data-width="<?php echo esc_attr($ad_width); ?>">
				<?php
				if ('teads' == $ad_location) {
				?>
					<div id="teads">
						<div id='teads-outstream' style='margin: auto; width: 400px; max-width: 100%;'>
							<script>
								googletag.cmd.push(function() {
									googletag.display('teads-outstream');
								});
							</script>
						</div>
					</div>
				<?php
				} else if ('teads2' == $ad_location) {
				?>
					<div id="teads2">
						<div id="teads-outstream2" style='margin: auto; width: 400px; max-width: 100%;'></div>
					</div>
					<script>
						googletag.cmd.push(function() {
							var adslot_teads2 = googletag.defineSlot('/9876188/rollingstoneau/teads', [1, 1], 'teads-outstream2')
								.setTargeting('pagepath', 'teads2')
								.addService(googletag.pubads());
							googletag.pubads().refresh([adslot_teads2]);
						});
					</script>
				<?php
				} else if ('teadsX1' == $ad_location || 'teadsX2' == $ad_location) {
					$uniqid = uniqid();
				?>
					<div id="teadsX<?php echo $uniqid; ?>">
						<div id="teads-outstreamX<?php echo $uniqid; ?>" style='margin: auto; width: 400px; max-width: 100%;'></div>
					</div>
					<script>
						googletag.cmd.push(function() {
							var adslot_teadsX<?php echo $uniqid; ?> = googletag.defineSlot('/9876188/rollingstoneau/teads', [1, 1], 'teads-outstreamX<?php echo $uniqid; ?>')
								.setTargeting('pagepath', 'teads<?php echo $uniqid; ?>')
								.addService(googletag.pubads());
							googletag.pubads().refresh([adslot_teadsX<?php echo $uniqid; ?>]);
						});
					</script>
					<script>
						/*
			(function(w, d) {
			  try {
			    d = w.top.document || d; w = w.top.document ? w.top : w;
			  } catch (e) {}
			  var ttag = function() {
			    try{
			var _tt_slot = frameElement.parentNode.parentNode;
			}catch(e){}

			    w.teads.page(119515).placement(129643, {slider: {allow_corner_position: true, allow_top_position: true}, "css":" padding: 18.75px 0px;","format":"inread","slot":{"selector":"#teads-outstreamX<?php echo $uniqid; ?>","minimum":1}}).serve();
			  };
			  if (w.teads && w.teads.page) { ttag(); }
			  else if (!w.teadsscript) {
			    var s<?php // echo $uniqid; 
						?> = document.createElement('script');
			    s<?php // echo $uniqid; 
					?>.src = 'https://s8t.teads.tv/media/format/v3/teads-format.min.js';
			    s<?php // echo $uniqid; 
					?>.async = true; s<?php // echo $uniqid; 
										?>.onload = ttag; w.teadsscript = d.getElementsByTagName('head')[0].appendChild(s);
			  } else {
			    w.teadsscript.addEventListener('load', ttag);
			  }
			})(window, document);
			*/
					</script>
				<?php
				} else if ('teads3' == $ad_location) {
				?>
					<div id="teads3">
						<div id="teads-outstream3" style='margin: auto; width: 400px; max-width: 100%;'></div>
					</div>
					<script>
						googletag.cmd.push(function() {
							var adslot_teads3 = googletag.defineSlot('/9876188/rollingstoneau/teads', [1, 1], 'teads-outstream3')
								.setTargeting('pagepath', 'teads3')
								.addService(googletag.pubads());
							googletag.pubads().refresh([adslot_teads3]);
						});
					</script>
				<?php
				} else if ('skin' == $ad_location) {
				?>
					<div id='adm_skin'>
						<script>
							googletag.cmd.push(function() {
								googletag.display('adm_skin');
							});
						</script>
					</div>
				<?php
				} else if (in_array($ad_location, ['hrec_1', 'leaderboard'])) {
				?>
					<div id='adm_leaderboard-desktop'>
						<script>
							googletag.cmd.push(function() {
								if (screen.width >= 970) {
									googletag.display('adm_leaderboard-desktop');
								}
							});
						</script>
					</div>
				<?php
				} else if ('hrec_2' == $ad_location) {
				} else if ('header_mobile' == $ad_location) {
				?>
					<div id='adm_leaderboard-mobile'>
						<script>
							googletag.cmd.push(function() {
								if (screen.width < 970) {
									googletag.display('adm_leaderboard-mobile');
								}
							});
						</script>
					</div>
					<?php
				} else if (in_array($ad_location, ['mrec', 'rail1'])) {
					if (1 == $slot_no) :
					?>
						<div id='adm_rail1' style='width: 300px; margin: 1rem auto;'>
							<script>
								googletag.cmd.push(function() {
									googletag.display('adm_rail1');
								});
							</script>
						</div>
					<?php
					else : ?>
						<!-- <div id='adm_rail1<?php echo $slot_no; ?>' style='width: 300px; margin: 1rem auto;'>
          <script>
              googletag.cmd.push(function() {
                  var adslot_rail_X<?php echo $slot_no; ?> = googletag.defineSlot('/9876188/rollingstoneau/homepage_railX', [300, 250], 'adm_rail1<?php echo $slot_no; ?>')
						  .addService(googletag.pubads())
						  .setTargeting('count', '<?php echo $slot_no; ?>');
                  googletag.pubads().refresh([adslot_rail_X<?php echo $slot_no; ?>]);
              });
          </script>
        </div> -->
					<?php
					endif;
				} else if (in_array($ad_location, ['rail2', 'half_page'])) {
					if (1 == $slot_no) :
					?>
						<div id='adm_rail2' style='width: 300px; margin: 1rem auto;'>
							<script>
								googletag.cmd.push(function() {
									googletag.display('adm_rail2');
								});
							</script>
						</div>
					<?php
					else : ?>
						<div id='adm_rail2<?php echo $slot_no; ?>' style='width: 300px; margin: 1rem auto;'>
							<script>
								googletag.cmd.push(function() {
									var adslot_rail_2<?php echo $slot_no; ?> = googletag.defineSlot('/9876188/rollingstoneau/homepage_rail2', [
											[300, 600],
											[300, 250]
										], 'adm_rail2<?php echo $slot_no; ?>')
										.addService(googletag.pubads())
										.setTargeting('count', '2<?php echo $slot_no; ?>');
									googletag.pubads().refresh([adslot_rail_2<?php echo $slot_no; ?>]);
								});
							</script>
						</div>
					<?php
					endif;
				} else if ('inbody1' == $ad_location) {
					if (1 == $slot_no) :
					?>
						<div id='adm_inbody1' style='width: auto; margin: 1rem auto;'>
							<script>
								googletag.cmd.push(function() {
									googletag.display('adm_inbody1');
								});
							</script>
						</div>
					<?php else : ?>
						<div id='adm_inbody1<?php echo $slot_no; ?>' style='width: auto; margin: 1rem auto;'>
							<script>
								googletag.cmd.push(function() {
									var adslot_inbody1<?php echo $slot_no; ?> = googletag.defineSlot('/9876188/rollingstoneau/music_article_inbody1', [300, 250], 'adm_inbody1<?php echo $slot_no; ?>')
										.addService(googletag.pubads())
										.setTargeting('count', '<?php echo $slot_no; ?>')
										.setTargeting('pos', 'inbody1');
									googletag.pubads().refresh([adslot_inbody1<?php echo $slot_no; ?>]);
								});
							</script>
						</div>
				<?php
					endif;
				} else if ('side_1' == $ad_location) {
					$slot_id = 'aurollingstonecom_side_1';
				} else if ('side_2' == $ad_location) {
					$slot_id = 'aurollingstonecom_side_3';
				} else if ('content_1' == $ad_location) {
					$slot_id = 'aurollingstonecom_content_1';
					if ($slot_no > 1) :
						$slot_id .= '-' . $slot_no;
					endif;
				} else if ('content_2' == $ad_location) {
					$slot_id = 'aurollingstonecom_content_2';
					if ($slot_no > 1) :
						$slot_id .= '-' . $slot_no;
					endif;
				} else if ('content_3' == $ad_location) {
					$slot_id = 'aurollingstonecom_content_3';
					if ($slot_no > 1) :
						$slot_id .= '-' . $slot_no;
					endif;
				} else if ('content_4' == $ad_location) {
					$slot_id = 'aurollingstonecom_content_4';
					if ($slot_no > 1) :
						$slot_id .= '-' . $slot_no;
					endif;
				} else if ('content_5' == $ad_location) {
					$slot_id = 'aurollingstonecom_content_5';
					if ($slot_no > 1) :
						$slot_id .= '-' . $slot_no;
					endif;
				} else if ('content_6' == $ad_location) {
					$slot_id = 'aurollingstonecom_content_6';
					if ($slot_no > 1) :
						$slot_id .= '-' . $slot_no;
					endif;
				}
				?>
			</div>
		</div>
	<?php
	}

	/*
	 * Get Next Post AJAX
	 */
	public function tbm_ajax_load_next_post()
	{
		global $post;

		// if ( get_field('paid_content', $_POST['id'] ) ) :
		//     wp_die();
		// endif;

		$exclude_posts = (!is_null($_POST['exclude_posts']) && $_POST['exclude_posts'] != '') ? $_POST['exclude_posts'] : '';
		$exclude_posts_array = explode(',', $exclude_posts);

		// $prevPostId = 13232;

		$tbm_featured_infinite_IDs = trim(get_option('tbm_featured_infinite_ID'));
		if ($tbm_featured_infinite_IDs) :
			$tbm_featured_infinite_IDs = array_map('trim', explode(',', $tbm_featured_infinite_IDs));
			$tbm_featured_infinite_IDs = array_map('absint', $tbm_featured_infinite_IDs);
			$tbm_featured_infinite_ID = $tbm_featured_infinite_IDs[array_rand($tbm_featured_infinite_IDs)];
		endif;

		// $tbm_featured_infinite_ID = '26901';

		// if (get_option('tbm_featured_infinite_ID') && $_POST['id'] != get_option('tbm_featured_infinite_ID') && !in_array(get_option('tbm_featured_infinite_ID'), $exclude_posts_array)) :
		// $prevPost = get_post(get_option('tbm_featured_infinite_ID'));
		if (isset($tbm_featured_infinite_ID) && $_POST['id'] != $tbm_featured_infinite_ID && !in_array($tbm_featured_infinite_ID, $exclude_posts_array)) :
			$prevPost = get_post($tbm_featured_infinite_ID);
		else :
			$post = get_post($_POST['id']);
			$prevPost = get_previous_post();
		endif;

		if (in_array($prevPost->ID, $exclude_posts_array)) {
			$data['content'] = '';
			$data['loaded_post'] = $prevPost->ID;
			wp_send_json_success($data);
			wp_die();
		}
		if ($prevPost) :
			$post = $prevPost;
			$data['exclude_post'] = $prevPost->ID;
			ob_start();
			$main_post = false;

			if ('26901' == $prevPost->ID) {
				// if ('25980' == $prevPost->ID) {
				echo '<style>
				.nav-network-wrap, .l-header__wrap.tbm { display: none !important; }
				.l-page, .is-header-sticky .l-header__wrap { max-width: 60.625rem; }';
				$top_px = 125;
				echo '
				.page-in-infinite {
					width: 930px;
					max-width: calc(100vw - 2.5rem);
					margin: 2rem auto;
				}
				.page-in-infinite .videos {
					min-height: 100vh !important;
					overflow-y: scroll !important;
				}
				
				</style><div class="page-in-infinite"><article>
				<h1 class="l-article-header__row l-article-header__row--title t-bold t-bold--condensed" data-href="' . get_the_permalink($prevPost->ID) . '" data-title="' . get_the_title($prevPost) . '" data-article-number="2" style="display: none;">' . get_the_title($prevPost) . '</h1>' . file_get_contents(home_url('?p=' . $tbm_featured_infinite_ID)) . '</article></div>';
			} else {
				include(get_template_directory() . '/template-parts/article/single.php');
			}

			// include(get_template_directory() . '/template-parts/article/single.php');

			wp_reset_query();
			wp_reset_postdata();
			$data['content'] = ob_get_clean();
			// $data['content'] .= $prevPostId;
			$data['loaded_post'] = $prevPost->ID;
			$data['page_title'] = html_entity_decode(get_the_title($prevPost));
			$author = get_the_author_meta('first_name', $post->post_author) . ' ' . get_the_author_meta('last_name', $post->post_author);
			// if ( get_field( 'author', $prevPost->ID ) ) {
			//     $author = get_field( 'author', $prevPost->ID );
			// } else if ( get_field( 'Author', $prevPost->ID ) ) {
			//     $author = get_field( 'Author', $prevPost->ID );
			// }
			$data['author'] = $author;

			$categories = get_the_category($prevPost->ID);
			if ($categories) {
				foreach ($categories as $category_obj) :
					$category = $category_obj->slug;
					break;
				endforeach;
				$data['category'] = $category;
			}

			$pagepath = parse_url(get_the_permalink($prevPost->ID), PHP_URL_PATH);
			$pagepath = substr(str_replace('/', '', $pagepath), 0, 40);
			$data['pagepath'] = $pagepath;

			wp_send_json_success($data);
		endif;
		wp_die();
	}

	/**
	 * This function registers the Post Options taxonomy. Its not meant to be called directly.
	 *
	 * @return void
	 */
	public function register_taxonomy()
	{
		register_taxonomy(
			'global-options',
			array('post'),
			array(
				'label'         => 'Post Option',
				'labels' => array(
					'name'               => __('Post Options', 'pmc-plugins'),
					'singular_name'      => __('Post Option', 'pmc-plugins'),
					'add_new_item'       => __('Add New Post Option', 'pmc-plugins'),
					'edit_item'          => __('Edit Post Option', 'pmc-plugins'),
					'new_item'           => __('New Post Option', 'pmc-plugins'),
					'view_item'          => __('View Post Option', 'pmc-plugins'),
					'search_items'       => __('Search Post Options', 'pmc-plugins'),
					'not_found'          => __('No Post Options found.', 'pmc-plugins'),
					'not_found_in_trash' => __('No Post Options found in Trash.', 'pmc-plugins'),
					'all_items'          => __('Post Options', 'pmc-plugins'),
				),
				'public'        => false,
				'show_ui'       => true,
				'hierarchical'  => true,
				'show_in_rest' => true,
				// admins only
				'capabilities'  => array(
					'manage_terms'  => 'edit_posts', // admin+
					'edit_terms'    => 'edit_posts', // admin+
					'delete_terms'  => 'edit_posts', // admin+
					'assign_terms'  => 'edit_posts', // contributor+
				),
			)
		);
	} // register_taxonomy

	/**
	 * Registers a styled heading for featured articles.
	 */
	public function register_styled_heading()
	{
		if (class_exists('\PMC\Styled_Heading\Styled_Heading')) {
			\PMC\Styled_Heading\Styled_Heading::register_styled_heading(__('Featured Article Hero Heading', 'pmc-rollingstone'), 'rollingstone_featured_article_styled_heading');
		}
	}

	/*
	* YouTube Shortcode
	*/
	public function youtube_shortcode_func($atts)
	{
		$a = shortcode_atts(array(
			'id' => 'ID',
		), $atts);
		ob_start();
	?>
		<div style="position: relative; width:100%; cursor:pointer; overflow:hidden;" title="Click to play video" class="yt-lazy-load home-featured-content" data-id="<?php echo $a['id']; ?>" id="<?php echo $a['id']; ?>">
			<img src="https://i.ytimg.com/vi/<?php echo $a['id']; ?>/sddefault.jpg" style="position: absolute; width: 100%; z-index: 1;top:50%;left:50%;transform:translate(-50%, -50%)" class="video-thumb">
			<img class="play-button-red" src="<?php echo TBM_CDN; ?>/assets/images//play-button-60px.png" style="width: 40px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 2;transition: .25s all linear;" alt="Play" title="Play">
		</div>
	<?php
		$content = ob_get_clean();
		return $content;
	}

	/*
	* Advert Shortcode
	*/
	public function advert_shortcode_func($atts)
	{
		ob_start();
		$content = ''; // ob_get_clean();
		return $content;
	}

	function custom_columns($columns)
	{
		$columns['custom_author'] = 'Custom Author';
		return $columns;
	}
	function edit_columns($column, $post_id)
	{
		switch ($column) {
			case "custom_author":
				if (get_field('author', $post_id)) {
					echo get_field('author', $post_id);
				}
				break;
		}
	}

	/*
	* Custom OG Image via Yoast plugin
	*/
	public function custom_opengraph_image_url($url)
	{
		if (get_field('thumbnail_ext_url')) {
			echo '<meta property="og:image" content="' . get_field('thumbnail_ext_url')   . '" />';
		}
	}

	/*
	* Inject Dailymotion at content end
	*/
	public function shortcode_dailymotion_playlist()
	{
		if (get_field('disable_ads') || get_field('disable_ads_in_content')) :
			return;
		endif;

		if (function_exists('is_amp_endpoint') && is_amp_endpoint()) {
			return;
		}

		if (has_term('featured-article', 'global-options')) :
			return;
		endif;

		$post_id = get_the_ID();

		ob_start();
	?>
		<div id="dm-player<?php $post_id; ?>"></div>
		<script>
			//id of div where init the player
			const divId = "dm-player<?php $post_id; ?>";

			//Which sort to use. List of available values: https://developer.dailymotion.com/api#video-sort-filter
			const sort = "recent";

			//xid OR username of the channels to search separated by ","
			const owners = "rollingstoneau";

			//search in a playlit ? provide playlist xid OR false
			const searchInPlaylist = "x6mqi7";

			//syndication key
			const syndication = "";

			//video auto play
			const autoPlayMute = true;

			//enable video queue
			const queueEnable = true;

			//enable video queue autoplay next
			const queueEnableNext = true;

			//shows player controls
			const controls = true;

			/**
			 * Define new event to listen
			 */
			const e = new Event('relatedSearchFinish');

			window.addEventListener('relatedSearchFinish', (e) => {
				initRelatedPlayer();
			});

			const playerParams = {
				'width': "100%",
				'height': "100%",
				'video': "",
				'params': {
					'controls': controls,
					'autoplay-mute': autoPlayMute,
					'queue-enable': queueEnable,
					'queue-autoplay-next': queueEnableNext,
					'syndication': syndication,
					'ads_params': 'contextual'
				}
			}

			const getVideo = () => {
				// Define current time and 30 days
				const url = "https://api.dailymotion.com/videos?owners=" + owners + "&sort=random&limit=1&fields=id";
				fetch(url).then(response => {
					return response.json();
				}).then(data => {
					if (data.list.length > 0) {
						/**
						 * Data return array, get the first array and pass it to setVideo function
						 */
						setVideo(data.list[0].id);
					} else {
						// console.warn("DM related Unable to find a fallback video");
					}
				});
			}


			const initRelatedPlayer = function() {
				if (document.getElementById(divId) !== null) {
					const elm = document.getElementById(divId);
					const cpeEmbed = document.createElement("div");

					const vid_pool = ['x7xur9p', 'x7xur31', 'x7xqepx', 'x7xo2gh', 'x7xlhfp', 'x7xlew9', 'x7xk77g', 'x7x3nxj', 'x7wu2gc', 'x7wnnfd', 'x7wilmn', 'x7whucz', 'x7wfq0m', 'x7wd7oq'];
					let random_vid = vid_pool[Math.floor(Math.random() * vid_pool.length)];

					cpeEmbed.setAttribute("class", "dailymotion-cpe");
					cpeEmbed.setAttribute("video-id", random_vid);
					// cpeEmbed.setAttribute("no-queue", true);
					// cpeEmbed.setAttribute("no-autonext", true);
					cpeEmbed.setAttribute("Playlist-id", searchInPlaylist);
					cpeEmbed.setAttribute("query-string", "sharing-enable=false");


					if (elm.getAttribute("width") !== null) {
						playerParams.width = elm.getAttribute("width");
						cpeEmbed.setAttribute("width", elm.getAttribute("width"));
					}

					if (elm.getAttribute("height") !== null) {
						playerParams.height = elm.getAttribute("height");
						cpeEmbed.setAttribute("height", elm.getAttribute("height"));
					}

					let cpeId = "5df6e56e3e8c0962957f6a76";

					if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent))
						cpeId = "5e3d40673e8c09313a2f7b78";

					elm.appendChild(cpeEmbed);
					(function(w, d, s, u, n, i, f, g, e, c) {
						w.WDMObject = n;
						w[n] = w[n] || function() {
							(w[n].q = w[n].q || []).push(arguments);
						};
						w[n].l = 1 * new Date();
						w[n].i = i;
						w[n].f = f;
						w[n].g = g;
						e = d.createElement(s);
						e.async = 1;
						e.src = u;
						c = d.getElementsByTagName(s)[0];
						c.parentNode.insertBefore(e, c);
					})(window, document, "script", "//api.dmcdn.net/pxl/cpe/client.min.js", "cpe", cpeId)

				}
			}

			const setVideo = (videoId) => {

				playerParams.video = videoId;
				window.dispatchEvent(e);
			}

			getVideo();
			// initRelatedPlayer();
		</script>
		<?php
		$dm_tag = ob_get_contents();
		ob_end_clean();

		return $dm_tag;
	}

	/*
	* Inject Dailymotion at content end
	*/
	public function shortcode_social_share()
	{
		global $post;
		$post_url = (get_permalink());

		$post_title = str_replace(' ', '%20', get_the_title());

		$twitterURL = 'https://twitter.com/intent/tweet?text=' . $post_title . '&amp;url=' . urlencode($post_url . '?utm_source=Twitter&amp;utm_content=Twitter_share_btn');
		$facebookURL = 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($post_url . '?utm_source=Facebook&utm_content=FB_share_btn');
		//    $googleURL = 'https://plus.google.com/share?url=' . $post_url . '&amp;text=' . $post_title . '&amp;hl=en_AU';
		$redditURL = 'https://reddit.com/submit?url=' . $post_url . '&amp;title=' . urlencode($post_title . '?utm_source=Reddit&utm_content=Reddit_share_btn');
		$whatsappURL = 'https://wa.me/?text=' . urlencode(get_the_title()) . ' ' . urlencode($post_url . '?utm_source=Whatsapp&utm_content=WA_share_btn');

		$html_content = '<div class="social-share-buttons nav">';
		$html_content .= '<a class="social-share-link social-share-facebook nav-link" id="social-share-facebook" href="' . $facebookURL . '" target="_blank" data-type="share-fb"><svg><use xlink:href="#svg-icon-facebook"></use></svg> <span class="d-none d-lg-inline">Share</span></a>';
		$html_content .= '<a class="social-share-link social-share-twitter nav-link" id="social-share-twitter" href="' . $twitterURL . '" target="_blank" data-type="share-twitter"><svg><use xlink:href="#svg-icon-twitter"></use></svg> <span class="d-none d-lg-inline">Tweet</span></a>';
		// $html_content .= '<a class="social-share-link social-share-reddit nav-link" id="social-share-reddit" href="' . $redditURL . '" target="_blank" data-type="share-reddit"><i class="fa fa-reddit-alien"></i></a>';
		// $html_content .= '<a class="social-share-link social-share-whatsapp nav-link" id="social-share-whatsapp" href="' . $whatsappURL . '" target="_blank" data-type="share-whatsapp"><i class="fa fa-whatsapp"></i></a>';
		$html_content .= '</div>';
		$html_content .= '
		<style>
		.social-share-buttons {
			width: 300px;
			margin: 2rem auto;
			display: flex;
			justify-content: center;
			align-items: center;
		}
		.social-share-buttons .social-share-link {
			padding: .5rem 1rem;
			display: flex;
			justify-content: center;
			align-items: center;
			color: #fff;
			margin: auto .5rem;
			border-radius: .25rem;
			background-color: #d32531;
			transition: .25s all linear;
		}
		.social-share-buttons .social-share-link:hover {
			background-color: transparent;
			color: #000;
		}

		.social-share-buttons .social-share-link svg {
			width: 1.125rem;
    		height: 1.125rem;
			margin-right: .5rem;
		}

		.social-share-buttons .social-share-link.social-share-facebook {
			background-color: #4267B2;
			color: #fff;
			border: 1px solid #4267B2;
		}
		.social-share-buttons .social-share-link.social-share-facebook:hover {
			background-color: #fff;
			color: #4267B2;
			border: 1px solid #4267B2;
		}
		.social-share-buttons .social-share-link.social-share-facebook svg {
			fill: #fff;
		}
		.social-share-buttons .social-share-link.social-share-facebook:hover svg {
			fill: #4267B2;
		}
		
		.social-share-buttons .social-share-link.social-share-twitter {
			background-color: #1DA1F2;
			color: #fff;
			border: 1px solid #1DA1F2;
		}
		.social-share-buttons .social-share-link.social-share-twitter:hover {
			background-color: #fff;
			color: #1DA1F2;
			border: 1px solid #1DA1F2;
		}
		.social-share-buttons .social-share-link.social-share-twitter svg {
			fill: #fff;
		}
		.social-share-buttons .social-share-link.social-share-twitter:hover svg {
			fill: #1DA1F2;
		}
		
		</style>
		<script>
		jQuery(document).ready(function($){
		$(\'.social-share-buttons .social-share-link\').click(function(event) {
			event.preventDefault();
			window.open($(this).attr("href"), "popupWindow", "width=600,height=600,scrollbars=yes");
		});
		});
		</script>
		';
		return $html_content;
	}


	/*
	* Inject Ads in content
	*/
	public function inject_ads($content)
	{

		if (get_field('disable_ads') || get_field('disable_ads_in_content')) :
			return $content;
		endif;

		if (function_exists('is_amp_endpoint') && is_amp_endpoint()) {
			return $content;
		}

		if (is_singular('page')) {
			return $content;
		}

		if (
			get_field('paid_content') || is_singular(array('tonegrant', 'phonedeaf')) ||
			(get_field('promoter') && 'livenation' == strtolower(get_field('promoter'))) ||
			is_page_template('single-template-featured.php')
			||
			'post' != get_post_type()
		) :
			return $content;
		endif;

		$count_articles = isset($_POST['count_articles']) ? (int) $_POST['count_articles'] : 1;

		$after_para = get_field('ads_after') ?: 1;

		ob_start();
		self::render_ads('incontent_1', '', '', $count_articles . '0');
		$content_ad_tag = ob_get_contents();
		ob_end_clean();
		if ($after_para == 0) {
			$content = '<div class="ad-mrec" id="ad-incontent-' . $count_articles . '">' . $content_ad_tag . '</div>' . $content;
		} else {
			$content = $this->insert_after_paragraph('<div class="ad-mrec" id="ad-incontent-' . $count_articles . '">' . $content_ad_tag . '</div>', $after_para, $content);
		}

		return $content;
	}

	public function insert_after_paragraph($insertion, $paragraph_id, $content)
	{
		$closing_p = '</p>';
		$paragraphs = explode($closing_p, $content);
		if (count($paragraphs) <= ($paragraph_id + 1)) :
			return $content;
		endif;
		foreach ($paragraphs as $index => $paragraph) {
			if (trim($paragraph)) {
				$paragraphs[$index] .= $closing_p;
			}
			if ($paragraph_id == $index + 1) {
				$paragraphs[$index] .= $insertion;
			}
		}
		return implode('', $paragraphs);
	}

	public function fbInstantArticleRSS()
	{
		add_feed('instant_articles', array($this, 'fbInstantArticle'));
	}
	public function fbInstantArticle()
	{
		get_template_part('rss', 'instant_articles');
	}

	/*
	 * YouTube Lazy Load
	 */
	public function youtube_lazy_load($content)
	{
		$pattern = '/<iframe(.*?)width=\"(.*)\"(.*?)height=\"(.*)\"(.*?)src=\"https:\/\/www.youtube.com\/embed\/(.*)\?(.*?)\" (.*)><\/iframe>/';
		$replacement = '<div style="text-align: center;"><div class="yt-lazy-load py-4" data-id="$6" id="yt-$6"><img data-src="https://i.ytimg.com/vi/$6/hqdefault.jpg" width="$2" height="$4" class="yt-img lazyload"><img class="play-button-red" src="' . get_template_directory_uri() . '/images/play-button-60px.png" alt="Play" title="Play"></div></div>';
		$lazy_content = preg_replace($pattern, $replacement, $content);
		return $lazy_content;
	}

	public function admin_enqueue_scripts($hook)
	{
		if (!in_array($hook, array('post.php', 'post-new.php'))) {
			return;
		}
		wp_enqueue_script('tbm-required-apple-news', get_template_directory_uri() . '/admin/js/scripts.js', array(
			'jquery', 'wp-data', 'wp-editor', 'wp-edit-post'
		));
	}

	/*
	* Get / Set Cookie (AJAX)
	*/
	public function tbm_ajax_get_cookie()
	{
		if (isset($_POST['cookie'])) :
			if ($_COOKIE[$_POST['cookie']]) :
				wp_send_json_success($_COOKIE[$_POST['cookie']]);
				wp_die();
			endif;
		endif;
	}
	public function tbm_ajax_set_cookie()
	{
		$data = isset($_POST) ? $_POST : [];
		$this->tbm_set_cookie($data);
		wp_die();
	}

	private function tbm_set_cookie($data)
	{
		if (!empty($data) && isset($data['key']) && isset($data['value']) && isset($data['duration'])) :
			setcookie($data['key'], $data['value'], time() + (int) $data['duration'], '/', $_SERVER['HTTP_HOST']);
		endif;
	}

	public function password_post_filter($where = '')
	{
		if (!is_page() && !is_single() && !current_user_can('edit_posts') && !is_admin()) {
			global $wpdb;
			$where .= " AND {$wpdb->prefix}posts.post_password = ''";
		}
		return $where;
	}

	public function post_thumbnails_in_feeds($content)
	{
		global $post;
		if (has_post_thumbnail($post->ID)) {
			$img_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
			$content = '<figure><img src="' . $img_src[0] . '" class="type:primaryImage"></figure>' . $content;
		}
		return $content;
	}

	public function inject_gotchosen()
	{
		if (is_single()) {
			$publisher_id = 'GC_91fbb7971c23fddfc2410e7405920acb6f6bc19b';
		?>
			<script>
				(function(c, e, n, o, i, r, s, t, u, a, h, f, l, d, p) {
					s = "querySelector";
					a = new Date;
					d = 0;
					c["GotChosenObject"] = o;
					c[o] = c[o] || function() {
						(c[o].q = c[o].q || []).push(arguments);
						r = r || c[o].q.filter(function(t) {
							return t[0] === "init"
						})[0][1];
						p = function() {
							try {
								try {
									h = [];
									c[o].q[0][2].widgets.autoinstall.forEach(function(t) {
										h.push(t.selector)
									});
									h = h.join()
								} catch (t) {
									h = ".gcwp-carousel"
								}
								if (d < 6e4 && !e[s]("#" + r)) {
									if (e[s](h)) {
										f = e.createElement(n);
										f.id = r;
										f.async = 1;
										f.src = i + "/gcjs/" + r + "/gc.js?cb=" + a.toJSON().slice(0, 13);
										e.head.appendChild(f)
									} else {
										setTimeout(p, 100)
									}
									d += 100
								}
							} catch (t) {
								throw new Error(t)
							}
						};
						if (r) {
							p()
						}
					}
				})(window, document, "script", "gc", "https://cdn.gotchosen.com");
				gc("init", "<?php echo $publisher_id; ?>", {
					widgets: {
						autoinstall: [{
							selector: '.gcwp-carousel',
							insertion: 'into'
						}]
					}
				});
			</script>
		<?php
		}
	}

	public function remove_p_tags_around_script($content)
	{
		$content = str_replace('&nbsp;', '', $content);
		$wraped_content = preg_replace(
			'/<p( style=\".*?\")?>(<script .*?><\/script>)(.*)?<\/p>/',
			'$2',
			$content
		);
		return $wraped_content;
	}

	public function filter_ptags_on_images($content)
	{
		return preg_replace('/<p(.*)>(<img .* \/>)<\/p>/', '<figure>$2</figure>', $content);
	}

	public function remove_p_tags_around_iframes($content)
	{
		$content = str_replace('&nbsp;', '', $content);
		$wraped_content = preg_replace(
			'/<p( style=\".*?\")?>(<iframe .*?><\/iframe>)(.*)?<\/p>/',
			'<figure class="op-interactive">$2</figure>',
			$content
		);
		$wraped_content = preg_replace(
			'/<div class="video-embed">(<iframe .*?><\/iframe>)(.*)?<\/div>/',
			'<figure class="op-interactive">$1</figure>',
			$wraped_content
		);
		$wraped_content = str_replace('width="100%"', 'width="400"', $wraped_content);
		return $wraped_content;
	}

	public function links_autoblank($content)
	{
		$content = preg_replace('/(<a.*?)[ ]?target="(.*?)"(.*?)/', '$1$3', $content);
		$content = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $content);
		return $content;
	}

	public function wpseo_opengraph_image($url)
	{
		if (get_post_meta(get_the_ID(), 'thumbnail_ext_url', TRUE)) {
			return get_post_meta(get_the_ID(), 'thumbnail_ext_url', TRUE);
		}
		return $url;
	}
} // Enf of class

new ThemeSetup();

class TBMAds
{

	protected $plugin_title;
	protected $plugin_name;
	protected $plugin_slug;

	protected static $_instance;

	public function __construct()
	{

		$this->plugin_title = 'TBM Ads';
		$this->plugin_name = 'tbm_ads';
		$this->plugin_slug = 'tbm-ads';

		add_action('wp_enqueue_scripts', [$this, 'action_wp_enqueue_scripts']);
		add_action('wp_head', [$this, 'action_wp_head']);
	}

	/*
  * Enqueue JS
  */
	public function action_wp_enqueue_scripts()
	{
		if (
			is_page_template('page-templates/stream.php') ||
			is_page_template('page-templates/jim-beam-2022.php')
		)
			return;
		wp_enqueue_script('adm-fuse', 'https://cdn.fuseplatform.net/publift/tags/2/2375/fuse.js', [], '1');
	}

	/*
  * WP Head
  */
	public function action_wp_head()
	{
		if (
			is_page_template('page-templates/stream.php') ||
			is_page_template('page-templates/jim-beam-2022.php')
		)
			return;
		// if (!is_home() && !is_front_page()) 
		{
		?>
			<script type="text/javascript">
				const fusetag = window.fusetag || (window.fusetag = {
					que: []
				});

				fusetag.que.push(function() {
					googletag.pubads().enableSingleRequest();
					googletag.enableServices();
				});
			</script>
<?php
		}
	}

	/*
  * Singleton
  */
	public static function get_instance()
	{
		if (!isset(static::$_instance)) {
			static::$_instance = new TBMAds();
		}
		return static::$_instance;
	}

	/*
  * Get Ad Tag
  */
	public function get_ad($ad_location = '', $slot_no = 0, $post_id = null, $device = '', $ad_width = '')
	{
		if (
			is_page_template('page-templates/stream.php') ||
			is_page_template('page-templates/jim-beam-2022.php')
		)
			return;

		if ('' == $ad_location)
			return;

		$html = '';
		$fuse_tags = self::fuse_tags();

		if (isset($_GET['screenshot'])) {
			$pagepath = 'screenshot';
		} else if (isset($_GET['dfp_key'])) {
			$pagepath = $_GET['dfp_key'];
		} else if (is_home() || is_front_page()) {
			$pagepath = 'homepage';
		} else {
			$pagepath_uri = substr(str_replace(['/', 'beta'], '', $_SERVER['REQUEST_URI']), 0, 40);
			$pagepath_e = explode('?', $pagepath_uri);
			$pagepath = $pagepath_e[0];
		}

		if (function_exists('amp_is_request') && amp_is_request()) {
			if (isset($fuse_tags['amp'][$ad_location]['sticky']) && $fuse_tags['amp'][$ad_location]['sticky']) {
				$html .= '<amp-sticky-ad layout="nodisplay">';
			}
			$html .= '<amp-ad
        width=' . $fuse_tags['amp'][$ad_location]['width']
				. ' height=' . $fuse_tags['amp'][$ad_location]['height']
				. ' type="doubleclick"'
				. ' data-slot="' . $fuse_tags['amp']['network_id'] . $fuse_tags['amp'][$ad_location]['slot'] . '"'
				. '></amp-ad>';
			if (isset($fuse_tags['amp'][$ad_location]['sticky']) && $fuse_tags['amp'][$ad_location]['sticky']) {
				$html .= '</amp-sticky-ad>';
			}
			return $html;
		} else {

			if (in_array($ad_location, ['mrec1', 'mrec_1'])) {
				$ad_location = 'rail1';
			} elseif (in_array($ad_location, ['mrec2', 'mrec_2'])) {
				$ad_location = 'rail2';
			}

			$fuse_id = null;

			$post_type = get_post_type(get_the_ID());

			$section = 'homepage';
			if (is_home() || is_front_page()) {
				$section = 'homepage';
			} elseif (is_category()) {
				$term = get_queried_object();
				if ($term) {
					$category_parent_id = $term->category_parent;
					if ($category_parent_id != 0) {
						$category_parent = get_term($category_parent_id, 'category');
						$category = $category_parent->slug;
					} else {
						$category = $term->slug;
					}
				}
				$section = 'category';
			} elseif (is_archive()) {
				$section = 'category';
			} elseif (in_array($post_type, ['post', 'pmc_list', 'pmc_list_item'])) {
				$section = 'article';
				/* if ($slot_no == 2) {
					$section = 'second_article';
				} */

				$categories = get_the_category($post_id);
				if ($categories) {
					foreach ($categories as $category_obj) :
						$category = $category_obj->slug;
						break;
					endforeach;
				}
			}

			$tags = get_the_tags($post_id);
			if ($tags) {
				$tag_slugs = wp_list_pluck($tags, 'slug');
			}

			if (isset($section)) {
				$fuse_id = isset($fuse_tags[$section][$ad_location]) ? $fuse_tags[$section][$ad_location] : 0;
			} else {
				$fuse_id = $fuse_tags[$ad_location];
			}
			$html .= '<!--' . $post_id . ' | '  . $section . ' | ' . $ad_location . ' | ' . $slot_no . '-->';
			$html .= '<div data-fuse="' . $fuse_id . '" style="margin: auto;"></div>';

			if ($slot_no > 1) {
				$html .= '<script>
      fusetag.que.push(function(){
        fusetag.loadSlotById("' . $fuse_id . '");
       });
       </script>';
			} else {
				$html .= '<script type="text/javascript">';
				if (isset($category)) {
					$html .= 'fusetag.setTargeting("fuse_category", ["' . $category . '"]);';
				}
				if (isset($tag_slugs)) {
					$html .= 'fusetag.setTargeting("tbm_tags", ' . json_encode($tag_slugs) . ');';
				}
				if (isset($pagepath)) {
					$html .= 'fusetag.setTargeting("pagepath", ["' . $pagepath . '"]);';
				}
				$html .= 'fusetag.setTargeting("site", "rollingstoneau");';
				$html .= '</script>';
			}

			return $html;
		}
	}

	private static function fuse_tags()
	{
		return [
			'amp' => [
				'network_id' => '/22071836792/SSM_rollingstone/',
				'header' => [
					'width' => 320,
					'height' => 50,
					'slot' => 'AMP_Header',
				],
				'mrec_1' => [
					'width' => 300,
					'height' => 250,
					'slot' => 'AMP_mrec_1',
				],
				'mrec_2' => [
					'width' => 300,
					'height' => 250,
					'slot' => 'AMP_mrec_2',
				],
				'sticky_footer' => [
					'width' => 320,
					'height' => 50,
					'slot' => 'AMP_sticky_footer',
					'sticky' => true
				]
			],
			'article' => [
				'skin' =>   '22378668622',
				'leaderboard' =>   '22378668229',

				'mrec' =>   '22378668643',
				'rail1' =>   '22378668643',

				'vrec' =>   '22378668619',
				'rail2' =>   '22378668619',

				'incontent_1' =>   '22378668628',
				'inbody1' =>   '22378668628',

				'incontent_2' =>   '22378668637',
				'content_6' =>   '22378668637',

				'desktop_sticky' =>   '22378668640',
				'mob_sticky' =>   '22378668232',
			],
			'second_article' => [
				'skin' =>   '22378668646',
				'leaderboard' =>   '22378668652',

				'mrec' =>   '22378668655',
				'rail1' =>   '22378668655',

				'vrec' =>   '22378668238',
				'rail2' =>   '22378668238',

				'incontent_1' =>   '22378668649',
				'inbody1' =>   '22378668649',

				'incontent_2' =>   '22378668241',
			],
			'category' => [
				'skin' =>   '22378668631',

				'leaderboard' =>   '22378668610',

				'mrec' =>   '22378668616',
				'rail1' =>   '22378668616',

				'vrec' =>   '22378668625',
				'rail2' =>   '22378668625',

				'desktop_sticky' =>   '22378668235',
				'mob_sticky' =>   '22378668634',
			],
			'homepage' => [
				'desktop_sticky' =>   '22378668589',

				'vrec_1' =>   '22378668214',
				'rail1' =>   '22378668214',

				'vrec_2' =>   '22378668586',
				'rail2' =>   '22378668586',

				'vrec_3' =>   '22378668583',
				'vrec_4' =>   '22378668592',
				'vrec_5' =>   '22378668595',
				'vrec_6' =>   '22377804323',
				'vrec_7' =>   '22378668217',

				'header' =>   '22378668580',
				'leaderboard' =>   '22378668580',

				'skin' =>   '22378668601',

				'incontent_1' =>   '22378668598',
				'inbody1' =>   '22378668598',
				'inbodyX' =>   '22378668598',

				'incontent_2' =>   '22378668223',
				'inbody2' =>   '22378668223',

				'incontent_3' =>   '22378668613',

				'incontent_4' =>   '22378668604',
				'incontent_5' =>   '22378668220',

				'incontent_6' =>   '22378553511',

				'mob_sticky' =>   '22378668226',
			]
		];
	}
}

TBMAds::get_instance();
