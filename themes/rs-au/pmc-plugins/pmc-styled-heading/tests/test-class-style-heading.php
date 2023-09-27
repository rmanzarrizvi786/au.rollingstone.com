<?php
/**
 * Test Class for Styled_Heading class
 * @since 2018-05-21
 *
 */

use PMC\Styled_Heading\Styled_Heading;

/**
 * @group pmc-styled-heading
 * @coversDefaultClass \PMC\Styled_Heading\Styled_Heading
 */
class Test_Class_Styled_Heading extends WP_UnitTestCase {

	/**
	 * Setup Method
	 */
	public function setUp() {
		// To speed up unit test, we bypass files scanning on upload folder.
		self::$ignore_files = true;
		parent::setUp();

		$this->clean_up_global_scope();

		$test_meta = array(
			'text_lines'       => array(
				'text_line' => array(
					0 => array(
						'text'           => 'A',
						'display'        => 'block',
						'color'          => '#ffffff',
						'font-size'      => '24',
						'font-weight'    => '400',
						'letter-spacing' => '0',
					),
					1 => array(
						'text'           => 'Big',
						'display'        => 'block',
						'color'          => '#ffffff',
						'font-size'      => '48',
						'font-weight'    => '400',
						'letter-spacing' => '0.06',
					),
					2 => array(
						'text'           => 'Title',
						'display'        => 'block',
						'color'          => '#000',
						'font-size'      => '32',
						'font-weight'    => '400',
						'letter-spacing' => '0',
					),
					3 => array(
						'display'        => 'block',
						'color'          => '#000',
						'font-size'      => '24',
						'font-weight'    => '400',
						'letter-spacing' => '0',
					),
					4 => array(
						'display'        => 'block',
						'color'          => '#000',
						'font-size'      => '24',
						'font-weight'    => '400',
						'letter-spacing' => '0',
					),
				),
			),
			'container_fields' => array(
				'max-width'        => '',
				'padding-top'      => '24',
				'padding-right'    => '24',
				'padding-bottom'   => '24',
				'padding-left'     => '24',
				'text-align'       => 'center',
				'background-color' => '',
				'border-color'     => '#ffffff',
				'border-width'     => '1',
				'border-style'     => 'solid',
				'margin'           => 'auto',
			),
		);

		$this->post = $this->factory->post->create_and_get();

		update_post_meta( $this->post->ID, Styled_Heading::FILTER_PREFIX . 'my_test_meta', $test_meta );

		$this->post = get_post( $this->post->ID );
	}

	public function tearDown() {
		parent::tearDown();

		unset( $GLOBALS['current_screen'] );

	}

	/**
	 * To prevent all upload files from deletion, since set $ignore_files = true
	 * we override the function and do nothing here
	 */
	public function remove_added_uploads() {}

	/**
	 * @covers ::register_styled_heading
	 * @expectedException Exception
	 */
	public function test_register_styled_heading() {

		Styled_Heading::register_styled_heading( 'My Heading', 'my_test_meta' );

		$this->assertTrue( has_action( 'fm_post_post' ) );
		$this->assertEquals( 10, has_action( 'admin_enqueue_scripts', array( Styled_Heading::class, 'enqueue_admin_style' ) ) );
		$this->assertEquals( 10, has_filter( 'safe_style_css', array( Styled_Heading::class, 'allow_display_css' ) ) );

		Styled_Heading::register_styled_heading( 'My Heading', 'my_test_meta' );
	}

	/**
	 * @covers ::get_styled_heading
	 */
	public function test_get_styled_heading() {

		$html = Styled_Heading::get_styled_heading( 'my_test_meta', $this->post->ID );

		$this->assertNotFalse( strpos( $html, 'span' ) );

	}

	/**
	 * @covers ::allow_display_css
	 */
	public function test_allow_display_css() {
		$styles = Styled_Heading::allow_display_css( [] );

		$this->assertEquals( [ 'display' ], $styles );
	}

	/**
	 * @covers ::enqueue_admin_style
	 */
	public function test_enqueue_admin_style() {

		Styled_Heading::enqueue_admin_style();

		$this->assertTrue( wp_style_is( 'pmc-styled-heading' ) );

	}

	/**
	 * @covers ::inline_style
	 */
	public function test_inline_style() {
		$styles = [
			'display'     => 'block',
			'font-size'   => '4',
			'not_a_style' => 'nothing',
		];

		ob_start();
		Styled_Heading::inline_style( $styles );

		$this->assertEquals( ' style="display:block;font-size:4px;"', ob_get_clean() );
	}

}
