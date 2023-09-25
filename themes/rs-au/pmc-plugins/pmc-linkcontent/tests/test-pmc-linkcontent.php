<?php

namespace PMC\LinkContent\Tests;

use PMC\Unit_Test\Utility;

/**
 * Class Test_PMC_LinkContent
 * @group pmc-linkcontent
 * @coversDefaultClass \PMC_LinkContent
 */
class Test_PMC_LinkContent extends Base {

	/**
	 * @var \PMC_LinkContent;
	 */
	protected $_instance;

	public function setUp() {
		parent::setUp();

		$this->_instance = \PMC_LinkContent::get_instance();
	}

	public function data_insert_field() {
		return [
			[
				'{"url":"http://pmc-wwd-2016.pmcdev.local/style/accessories/gallery/manager-gallery-104/","id":104,"title":"&quot;Manager&quot; Gallery"}',
				'{&quot;url&quot;:&quot;http://pmc-wwd-2016.pmcdev.local/style/accessories/gallery/manager-gallery-104/&quot;,&quot;id&quot;:104,&quot;title&quot;:&quot;\&quot;Manager\&quot; Gallery&quot;}',
				'assertContains'
			],
			[
				'{"url":"http://pmc-wwd-2016.pmcdev.local/style/accessories/gallery/manager-gallery-104/","id":104,"title":"Manager Gallery"}',
				'{&quot;url&quot;:&quot;http://pmc-wwd-2016.pmcdev.local/style/accessories/gallery/manager-gallery-104/&quot;,&quot;id&quot;:104,&quot;title&quot;:&quot;Manager Gallery&quot;}',
				'assertContains'
			],
			[
				'{"url":"http://pmc-wwd-2016.pmcdev.local/style/accessories/gallery/manager-gallery-104/","id":104,"title":""Manager" Gallery"}',
				'pmclinkcontent-remove',
				'assertNotContains'
			],
		];
	}

	/**
	 * @covers ::insert_field
	 * @dataProvider data_insert_field
	 */
	public function test_insert_field( $input, $expects, $assert ) {
		$output = Utility::buffer_and_return( [ $this->_instance, 'insert_field' ], [ $input ] );

		$this->$assert( $expects, $output );
	}
}

//EOF
