<?php

/**
 * @group pmc-global-functions
 * @group cdn
 *
 * PHPUnit tests for class PMC_CDN
 *
 * @since 2016-11-03 Hau Vong
 *
 */
namespace PMC\Global_Functions\Tests;
use PMC_Cheezcap;
use PMC_CDN;

class Tests_Class_PMC_CDN extends Base {
	public function setUp() {
		// to speed up unit test, we bypass files scanning on upload folder
		self::$ignore_files = true;

		parent::setUp();

		// need to unset this to prevent is_admin return true
		unset( $GLOBALS['current_screen'] );

	}

	public function remove_added_uploads() {
		// To prevent all upload files from deletion, since set $ignore_files = true
		// we override the function and do nothing here
	}

	/**
	 * @group pmc-phpunit-ignore-failed
	 */
	public function test_load_cdn_options() {

		// Jetpack plugin need to active to test photon url verification
		remove_all_filters( 'jetpack_photon_skip_for_url' );
		if ( ! is_plugin_active( 'jetpack' ) ) {
			$this->assertFalse( is_wp_error( activate_plugin( 'jetpack/jetpack.php' ) ) );
		}

		PMC_Cheezcap::get_instance()->register();
		$GLOBALS['cap']->pmc_cdn_host_static        = 'cloudflare=cfstatic,akamai=akstatic';
		$GLOBALS['cap']->pmc_cdn_host_media         = 'cloudflare=cfmedia,akamai=akmedia';
		$GLOBALS['cap']->pmc_cdn_host_photon        = 'https://phonton-cdn';
		$GLOBALS['cap']->pmc_cdn_host_media_origin  = 'origin-media-host';
		$GLOBALS['cap']->pmc_cdn_https              = 'on';
		$GLOBALS['cap']->pmc_cdn_activate_condition = 'cloudflare';
		$_SERVER['HTTPS']                           = 'on';
		$_SERVER['HTTP_CF_RAY']                     = 'Ray Id';

		add_filter(
			'pmc_custom_cdn_options', function( $cdn_options ) {
				$this->cdn_options = $cdn_options;
				return $cdn_options;
			}
		);

		// vary cache function to return cf, ak, or empty string
		$func = @create_function( '', PMC_CDN::get_instance()->get_vary_cache_on_function_string() ); // @codingStandardsIgnoreLine: To suppress errors on older version of php VIP needs this function for vary on cache. Ignored as part of WI-544 VIP approved in ticket https://wordpressvip.zendesk.com/hc/en-us/requests/78347

		PMC_CDN::get_instance()->load_cdn_options();

		$this->assertNotEmpty( $this->cdn_options );
		$this->assertArraySubset(
			[
				'cdn_host_media'  => 'cloudflare=cfmedia,akamai=akmedia',
				'cdn_host_static' => 'cloudflare=cfstatic,akamai=akstatic',
			], $this->cdn_options
		);
		$this->assertEquals( 'cf', call_user_func( $func ) );

		add_filter( 'jetpack_photon_development_mode', '__return_false', 9999 );

		// Expecting nothing change on photon urls
		$this->assertEquals( 'https://phonton-cdn/origin-media-host/image.jpg?ssl=1', jetpack_photon_url( 'https://origin-media-host/image.jpg' ) );
		$this->assertEquals( 'https://phonton-cdn/cfmedia/image.jpg?ssl=1', jetpack_photon_url( 'https://cfmedia/image.jpg' ) );
		$this->assertEquals( 'https://phonton-cdn/akmedia/image.jpg?ssl=1', jetpack_photon_url( 'https://akmedia/image.jpg' ) );

		// reset option to retest cdn activation
		unset( $this->cdn_options );
		$GLOBALS['cap']->pmc_cdn_activate_condition = 'cdn';
		PMC_CDN::get_instance()->load_cdn_options();
		$this->assertNotEmpty( $this->cdn_options );
		$this->assertArraySubset(
			[
				'cdn_host_media'  => 'cfmedia',
				'cdn_host_static' => 'cfstatic',
			], $this->cdn_options
		);

		// Expecting cfmedia translation
		$this->assertEquals( 'https://phonton-cdn/origin-media-host/image.jpg?ssl=1', jetpack_photon_url( 'https://origin-media-host/image.jpg' ) );
		$this->assertEquals( 'https://phonton-cdn/origin-media-host/image.jpg?ssl=1', jetpack_photon_url( 'https://cfmedia/image.jpg' ) );
		$this->assertEquals( 'https://phonton-cdn/akmedia/image.jpg?ssl=1', jetpack_photon_url( 'https://akmedia/image.jpg' ) );

		// done with cloudflare
		unset( $_SERVER['HTTP_CF_RAY'] );

		// Start test Akamai option
		unset( $this->cdn_options );
		$_SERVER['HTTP_VIA']                        = '1.1 akamai.net(ghost) (AkamaiGHost)';
		$GLOBALS['cap']->pmc_cdn_activate_condition = 'akamai';
		PMC_CDN::get_instance()->load_cdn_options();
		$this->assertNotEmpty( $this->cdn_options );
		$this->assertArraySubset(
			[
				'cdn_host_media'  => 'cloudflare=cfmedia,akamai=akmedia',
				'cdn_host_static' => 'cloudflare=cfstatic,akamai=akstatic',
			], $this->cdn_options
		);
		$this->assertEquals( 'ak', call_user_func( $func ) );

		// Expecting nothing change on photon urls
		$this->assertEquals( 'https://phonton-cdn/origin-media-host/image.jpg?ssl=1', jetpack_photon_url( 'https://origin-media-host/image.jpg' ) );
		$this->assertEquals( 'https://phonton-cdn/cfmedia/image.jpg?ssl=1', jetpack_photon_url( 'https://cfmedia/image.jpg' ) );
		$this->assertEquals( 'https://phonton-cdn/akmedia/image.jpg?ssl=1', jetpack_photon_url( 'https://akmedia/image.jpg' ) );

		unset( $this->cdn_options );
		$GLOBALS['cap']->pmc_cdn_activate_condition = 'cdn';
		PMC_CDN::get_instance()->load_cdn_options();
		$this->assertNotEmpty( $this->cdn_options );
		$this->assertArraySubset(
			[
				'cdn_host_media'  => 'akmedia',
				'cdn_host_static' => 'akstatic',
			], $this->cdn_options
		);

		// Expecting akmedia translation
		$this->assertEquals( 'https://phonton-cdn/origin-media-host/image.jpg?ssl=1', jetpack_photon_url( 'https://origin-media-host/image.jpg' ) );
		$this->assertEquals( 'https://phonton-cdn/cfmedia/image.jpg?ssl=1', jetpack_photon_url( 'https://cfmedia/image.jpg' ) );
		$this->assertEquals( 'https://phonton-cdn/origin-media-host/image.jpg?ssl=1', jetpack_photon_url( 'https://akmedia/image.jpg' ) );

		$this->assertTrue( apply_filters( 'pmc_custom_cdn_ssl_opt_in', false ) );

		$this->assertEquals( 'https://phonton-cdn/origin-media-host/image.jpg?ssl=1', jetpack_photon_url( 'https://origin-media-host/image.jpg' ) );

		$GLOBALS['cap']->pmc_cdn_host_static        = 'static.host.com';
		$GLOBALS['cap']->pmc_cdn_host_media         = 'media.host.com';
		$GLOBALS['cap']->pmc_cdn_host_photon        = '';
		$GLOBALS['cap']->pmc_cdn_host_media_origin  = 'origin.media.com';
		$GLOBALS['cap']->pmc_cdn_https              = 'on';
		$GLOBALS['cap']->pmc_cdn_activate_condition = 'all';
		PMC_CDN::get_instance()->load_cdn_options();

		$html = apply_filters( 'pmc_html_ssl_friendly', '<img href="https://origin.media.com/assets">https://origin.media.com/assets</a><img href="https://origin.media.com/assets">https://origin.media.com/assets</a>' );
		$this->assertEquals( $html, '<img href="https://media.host.com/assets">https://media.host.com/assets</a><img href="https://media.host.com/assets">https://media.host.com/assets</a>' );

		remove_filter( 'jetpack_photon_development_mode', '__return_false' );
	}
}

