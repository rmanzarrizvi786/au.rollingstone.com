<?php
namespace PMC\Global_Functions\Tests;
use PMC\Unit_Test\Utility;
use PMC\Global_Functions\Service\Http2;

class Test_Http2 extends Base {

	protected function _load_plugin() {
		Utility::set_and_get_hidden_static_property( Http2::class, '_instance', null );
		$_SERVER['HTTP_X_WP_CB'] = 1;
		$this->instance = Http2::get_instance();
	}

	public function test_load_plugin() {

		$instance = $this->assert_plugin_loaded( Http2::class );

		// Make sure our action & filter are attached when plugin activated
		$this->assertNotFalse( has_action( 'get_header', [ $instance, 'initialize_process' ] ) );

	}

	public function test_ob_callback() {
		global $wp_scripts, $wp_styles;

		wp_enqueue_script('unitest', home_url( '/unitest/script.js' ) );
		wp_enqueue_script('unitest-ver', home_url( '/unitest/script-ver.js' ), [], 'ver', false );
		wp_enqueue_style('unitest', site_url( '/unitest/style.css' ), [], null, false );

		wp_enqueue_script('exclude', '//domain.com/exclude.js', [], null, false );

		$wp_scripts = wp_scripts();
		$wp_styles  = wp_styles();

		$wp_scripts->done = $wp_styles->done = [ 'unitest', 'exclude', 'unitest-ver' ];

		$this->instance->set_pattern( [ '/_static\//' ] )
			->set_whitelist( [ '/unitest/loader_src/script.js' ] )
			->register_pattern( '/script/' )
			->register_pattern( '/unitest-long-string/' )
			->register_pattern( '///invalid-pattern' ) // invalid expression for code coverage
			->register_whitelist( '/unitest/style.css' );

		$links = [];
		add_filter( 'pmc_http2_preload_links', function( $assets ) use( &$links ) {
			$links = $assets;
			return $assets;
		} );

		add_filter( 'pmc_http2_preload_assets', function( $preloads ) {
			$preloads[] = [];  // code coverage for empty/invalid entry
			$preloads[] = [
					'as'  => 'script',
					'uri' => '/unitest-long-string/'. str_repeat('a',Http2::MAX_HEADER_SIZE * 2 )  // try to blow up the headers
				];
			return $preloads;
		} );

		apply_filters( 'script_loader_src', '/unitest/loader_src/script.js' );

		$html = "<html><head>
			<link rel='stylesheet' id='all-css-0' href='https://local.pmcdev.io/_static/??unitest-css' type='text/css' media='all' />
			<script type='text/javascript' src='https://local.pmcdev.io/_static/??unitest-js'></script>
			</head><body>unitest-body</body></html>";

		$this->assertContains( 'unitest-body', $this->instance->ob_callback( $html ), 'error validating ob_callback function' );

		$this->assertEquals( [
				'</unitest/style.css>; rel=preload; as=style',
				'</_static/??unitest-css>; rel=preload; as=style',
				'</unitest/loader_src/script.js>; rel=preload; as=script',
				'</unitest/script.js?ver='. $wp_scripts->default_version  .'>; rel=preload; as=script',
				'</unitest/script-ver.js?ver=ver>; rel=preload; as=script',
				'</_static/??unitest-js>; rel=preload; as=script',
			], $links );

		remove_all_filters( 'pmc_http2_preload_assets' );
		$wp_scripts->done = [];
		$wp_styles->done  = [];
		$this->assertEquals( '[{"uri":"/_static/??unitest-css","as":"style"},{"as":"script","uri":"/unitest/loader_src/script.js"},{"uri":"/_static/??unitest-js","as":"script"}]', json_encode( $this->instance->get_preload_assets( $html ), JSON_UNESCAPED_SLASHES ) );

	}


}