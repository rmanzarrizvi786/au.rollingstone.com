<?php
namespace PMC\Global_Functions\Tests;

use PMC\Unit_Test\Utility;

class Test_PMC_Ajax extends Base {
	public function test_construct() {
		$old_instance = Utility::get_instance( \PMC_Ajax::class );
		Utility::unset_singleton( \PMC_Ajax::class );

		$actions = [ 'template_redirect', 'init' ];
		foreach( $actions as $action ) {
			remove_all_actions( $action );
		}
		wp_dequeue_script( 'pmc-ajax' );
		$this->assertFalse( wp_script_is( 'pmc-ajax', 'enqueued' ) );

		$instance = \PMC_Ajax::get_instance();
		foreach ( $actions as $action ) {
			$this->assertTrue( 0 < has_action( $action ) );
		}

		do_action( 'init' );
		$this->assertTrue( wp_script_is( 'pmc-ajax', 'enqueued' ) );

	}
}