<?php
namespace PMC\Global_Functions\Tests;

use PMC_DOM;

/**
 * Unit tests for /classes/class-pmc-dom.php
 *
 * @group pmc-global-functions
 * @coversDefaultClass PMC_DOM
 *
 * @requires PHP 5.6
 *
 * @package pmc-global-functions
 */
class Test_PMC_DOM extends Base {

	function setUp() {

		// to speed up unit test, we bypass files scanning on upload folder.
		self::$ignore_files = true;

		parent::setUp();

	}

	/**
	 * To prevent all upload files from deletion, since set $ignore_files = true.
	 * we override the function and do nothing here.
	 */
	function remove_added_uploads() {}

	/**
	 * To test 'pmc_dom_insertions' filter
	 */
	public function filter_pmc_dom_insertions( $paragraphs ) {

		$paragraphs[][] = '<p>Add new content by filter</p>';

		return $paragraphs;

	}

	/**
	 * @covers ::inject_paragraph_content()
	 */
	public function test_inject_paragraph_content_with_should_apply_pmc_dom_insertions_filter_option() {

		$content_to_inject = 'Content to inject';
		$main_content      = 'Main Content...';

		/* Test with 'should_apply_pmc_dom_insertions_filter' = true. */

		add_filter( 'pmc_dom_insertions', [ $this, 'filter_pmc_dom_insertions' ] );

		$final_content = PMC_DOM::inject_paragraph_content(
			$main_content,
			[
				'should_apply_pmc_dom_insertions_filter' => true,
				'paragraphs'                             => [
					1 => [
						$content_to_inject,
					],
				],
			]
		);

		$this->assertContains( 'Main Content...', $final_content, 'Passed main content not found.' );

		$this->assertContains( 'Content to inject', $final_content, 'Passed paragraphs not injected.' );

		$this->assertContains( '<p>Add new content by filter</p>', $final_content, 'Filtered paragraph not injected' );


		/* Test with 'should_apply_pmc_dom_insertions_filter' = false. */

		$final_content = \PMC_DOM::inject_paragraph_content(
			$main_content,
			[
				'should_apply_pmc_dom_insertions_filter' => false,
				'paragraphs'                             => [
					1 => [
						$content_to_inject,
					],
				],
			]
		);

		$this->assertContains( 'Main Content...', $final_content, 'Passed main content not found.' );

		$this->assertContains( 'Content to inject', $final_content, 'Passed paragraphs not injected.' );

		$this->assertNotContains( '<p>Add new content by filter</p>', $final_content, 'Filtered paragraph injected' );

		remove_filter( 'pmc_dom_insertions', [ $this, 'filter_pmc_dom_insertions' ] );

	}

	/**
	 * @covers ::inject_paragraph_content()
	 */
	public function test_inject_paragraph_content_with_should_append_after_tag_option() {

		$content_to_inject = 'Content to inject';
		$main_content      = 'Main Content...';

		/* Test with 'should_append_after_tag' = false. */

		$final_content = PMC_DOM::inject_paragraph_content(
			$main_content,
			[
				'should_append_after_tag' => false,
				'paragraphs'              => [
					1 => [
						$content_to_inject,
					],
				],
			]
		);

		$this->assertContains( '<p>Main Content...Content to inject</p>', $final_content, 'Content not injected within p tag.' );

		/* Test with 'should_append_after_tag' = true. */

		$final_content = \PMC_DOM::inject_paragraph_content(
			$main_content,
			[
				'should_append_after_tag' => true,
				'paragraphs'              => [
					1 => [
						$content_to_inject,
					],
				],
			]
		);

		$this->assertContains( '<p>Main Content...</p>Content to inject', $final_content, 'Content not injected after p tag.' );

	}

}
