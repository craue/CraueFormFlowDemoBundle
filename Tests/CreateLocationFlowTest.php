<?php

namespace Craue\FormFlowDemoBundle\Tests;

use Craue\FormFlowDemoBundle\Model\Regions;

/**
 * @group integration
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2020 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CreateLocationFlowTest extends IntegrationTestCase {

	public function testCreateLocation() {
		static::$client->followRedirects();
		$crawler = static::$client->request('GET', $this->url('_FormFlow_createLocation'));

		$this->assertSame(200, static::$client->getResponse()->getStatusCode());
		$this->assertCurrentStepNumber(1, $crawler);

		$form = $crawler->selectButton('next')->form();

		$this->assertEquals('', $form->get('createLocationStep1[country]')->getValue());

		$expectedOptionsCountryTopChoices = [];
		foreach (['' => '', 'AT' => 'Austria', 'DE' => 'Germany', 'CH' => 'Switzerland', 'US' => 'United States'] as $value => $label) {
			$expectedOptionsCountryTopChoices[] = [
				'value' => $value,
				'label' => $label,
			];
		}
		$this->assertEquals($expectedOptionsCountryTopChoices, array_slice($this->getOptionsOfSelectField('#createLocationStep1_country', $crawler), 0, count($expectedOptionsCountryTopChoices), true));

		// invalid country -> step 1 again
		$form->disableValidation();
		$crawler = static::$client->submit($form, [
			'createLocationStep1[country]' => 'INVALID',
		]);
		$this->assertCurrentStepNumber(1, $crawler);
		try {
			$this->assertFieldHasError('#createLocationStep1_country', 'This value is not valid.', $crawler);
		} catch (\PHPUnit\Framework\ExpectationFailedException $e) {
			// TODO remove as soon as Symfony >= 3.4.21, >= 4.1.10, >= 4.2.2 is required
			$this->assertFieldHasError('#createLocationStep1_country', 'This value is not a valid country.', $crawler);
		}

		// country without region -> step 3
		$form = $crawler->selectButton('next')->form();
		$crawler = static::$client->submit($form, [
			'createLocationStep1[country]' => 'AX',
		]);
		$this->assertCurrentStepNumber(3, $crawler);

		$this->assertEquals([
			'country' => 'Åland Islands',
		], $this->getListContent('', $crawler));

		$form = $crawler->selectButton('back')->form();

		// go back -> step 1
		$crawler = static::$client->submit($form);
		$this->assertCurrentStepNumber(1, $crawler);

		$form = $crawler->selectButton('next')->form();

		$this->assertEquals('AX', $form->get('createLocationStep1[country]')->getValue());

		// country with region -> step 2
		$crawler = static::$client->submit($form, [
			'createLocationStep1[country]' => 'DE',
		]);
		$this->assertCurrentStepNumber(2, $crawler);

		$form = $crawler->selectButton('next')->form();

		$this->assertEquals('', $form->get('createLocationStep2[region]')->getValue());

		$expectedOptionsRegionTopChoices = [];
		foreach (['' => '', 'DE-BW' => 'Baden-Württemberg'] as $value => $label) {
			$expectedOptionsRegionTopChoices[] = [
				'value' => $value,
				'label' => $label,
			];
		}
		$actualRegionOptions = $this->getOptionsOfSelectField('#createLocationStep2_region', $crawler);
		$this->assertEquals($expectedOptionsRegionTopChoices, array_slice($actualRegionOptions, 0, count($expectedOptionsRegionTopChoices), true));
		$this->assertCount(1 + count(Regions::getRegionsForCountry('DE')), $actualRegionOptions);

		// invalid region -> step 2 again
		$form->disableValidation();
		$crawler = static::$client->submit($form, [
			'createLocationStep2[region]' => 'INVALID',
		]);
		$this->assertCurrentStepNumber(2, $crawler);
		$this->assertFieldHasError('#createLocationStep2_region', 'This value is not valid.', $crawler);

		// region -> step 3
		$form = $crawler->selectButton('next')->form();
		$crawler = static::$client->submit($form, [
			'createLocationStep2[region]' => 'DE-BE',
		]);
		$this->assertCurrentStepNumber(3, $crawler);

		$this->assertEquals([
			'country' => 'Germany',
			'region' => 'Berlin',
		], $this->getListContent('', $crawler));

		// finish flow
		$form = $crawler->selectButton('finish')->form();
		static::$client->submit($form);
		$this->assertEquals('_FormFlow_start', static::$client->getRequest()->attributes->get('_route'));
	}

}
