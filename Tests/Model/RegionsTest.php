<?php

namespace Craue\FormFlowDemoBundle\Tests\Model;

use Craue\FormFlowDemoBundle\Model\Regions;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2019 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class RegionsTest extends TestCase {

	/**
	 * @dataProvider dataGetRegionsForCountry_validCountry
	 */
	public function testGetRegionsForCountry_validCountry($country) {
		$this->assertNotEmpty(Regions::getRegionsForCountry($country));
	}

	public function dataGetRegionsForCountry_validCountry() {
		return [
			['AT'],
			['CH'],
			['DE'],
			['US'],
		];
	}

	/**
	 * @dataProvider dataGetRegionsForCountry_invalidCountry
	 */
	public function testGetRegionsForCountry_invalidCountry($country) {
		$this->assertEmpty(Regions::getRegionsForCountry($country));
	}

	public function dataGetRegionsForCountry_invalidCountry() {
		return [
			['INVALID'],
		];
	}

}
