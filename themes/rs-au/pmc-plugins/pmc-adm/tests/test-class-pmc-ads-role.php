<?php

/**
 * @group pmc-adm
 * @coversDefaultClass PMC_Ads_Role
 *
 */

class Test_Class_PMC_Ads_Role extends WP_UnitTestCase {

	protected $pmc_ads = null;

	/**
	 * Setup between each test.
	 */
	function setUp() {

		// do not report on warning to avoid unit test reporting on:
		// Cannot modify header information - headers already sent by ..
		error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );

		// to speeed up unit test, we bypass files scanning on upload folder
		self::$ignore_files = true;

		parent::setUp();

		$this->pmc_ads = PMC_Ads::get_instance();
		$this->pmc_ads->add_provider( new Google_Publisher_Provider( 'unittest' ) );
		$this->pmc_ads->add_provider( new Site_Served_Provider( 'ss-unit-test', [] ) );
	}

	/**
	 * TearDown Between Each Test.
	 */
	public function tearDown() {

		unset( $this->pmc_ads );

		parent::tearDown();
	}

	/**
	 * To prevent all upload files from deletion, since set $ignore_files = true
	 * we override the function and do nothing here.
	 */
	public function remove_added_uploads() {
	}

	/**
	 * @covers ::__construct
	 */
	public function test__construct() {
		$instance = PMC_Ads_Role::get_instance();

		$this->assertInstanceOf( "PMC_Ads_Role", $instance );

		$filters = array(
			'pmc_user_roles_override_capabilities_pmc-audience-marketing' => 'add_capabilities_for_audience_marketing',
			'pmc_user_roles_override_capabilities_pmc-adops-manager' => 'add_capabilities_for_adops'
		);

		foreach ( $filters as $key => $value ) {
			$this->assertNotEquals(
				false,
				has_filter( $key, array( $instance, $value ) ),
				sprintf( 'PMC_Ads_Role::_construct failed registering filter "%1$s" to PMC_Ads_Role::%2$s', $key, $value )
			);
		}

	}

	/**
	 * @covers ::get_providers_for_role()
	 */
	public function test_get_providers_for_role() {

		$u = $this->factory->user->create( array(
			'user_login' => 'admin',
			'user_pass'  => 'admin',
		) );

		$u = wp_set_current_user( $u );

		$u->add_role( 'administrator' );

		$all_providers  = PMC_Ads::get_instance()->get_providers();
		$role_providers = PMC_Ads_Role::get_instance()->get_providers_for_role( 'administrator' );

		$this->assertEquals( $all_providers, $role_providers, 'Administrator role did not fetch all providers' );

		$u->remove_role( 'administrator' );

		$all_adm_roles = PMC_Ads_Role::get_instance()->get_adm_roles();
		foreach ( $all_adm_roles as $adm_role ) {
			$u->add_role( $adm_role );

			$role_providers = PMC_Ads_Role::get_instance()->get_providers_for_role( $adm_role );
			$this->assertTrue( is_array( $role_providers ), $adm_role . ' did not fetch proper provider' );

			foreach ( $role_providers as $provider ) {
				$provider_id = $provider->get_id();
				$this->assertTrue( array_key_exists( $provider_id, $all_providers ), 'No valid provider fetched' );
			}

			$u->remove_role( $adm_role );
		}

	}

}