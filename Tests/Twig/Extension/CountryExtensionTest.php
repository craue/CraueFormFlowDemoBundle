<?php

namespace Craue\FormFlowDemoBundle\Tests\Twig\Extension;

use Craue\FormFlowDemoBundle\Twig\Extension\CountryExtension;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2020 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CountryExtensionTest extends TestCase {

	/**
	 * @var CountryExtension
	 */
	protected $ext;

	protected function setUp() : void {
		\Locale::setDefault('en');

		 $this->ext = new CountryExtension();
	}

	/**
	 * @dataProvider dataGetCountry
	 */
	public function testGetCountry($expectedValue, $country) {
		$this->assertSame($expectedValue, $this->ext->getCountry($country));
	}

	public function dataGetCountry() {
		return [
			[null, null],
			[null, ''],
			[null, 'INVALID'],
			['Germany', 'DE'],
		];
	}

}
