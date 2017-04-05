<?php

namespace Craue\FormFlowDemoBundle\Tests;

use Craue\FormFlowDemoBundle\Model\Regions;

/**
 * @group integration
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2017 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CreateLocationFlowTest extends IntegrationTestCase {

	public function testCreateLocation() {
		$this->client->followRedirects();
		$crawler = $this->client->request('GET', $this->url('_FormFlow_createLocation'));

		$this->assertSame(200, $this->client->getResponse()->getStatusCode());
		$this->assertCurrentStepNumber(1, $crawler);

		$form = $crawler->selectButton('next')->form();

		$this->assertEquals('', $form->get('createLocationStep1[country]')->getValue());

		$expectedOptionsCountryTopChoices = array();
		foreach (array('' => '', 'AT' => 'Austria', 'DE' => 'Germany', 'CH' => 'Switzerland', 'US' => 'United States') as $value => $label) {
			$expectedOptionsCountryTopChoices[] = array(
				'value' => $value,
				'label' => $label,
			);
		}
		$this->assertEquals($expectedOptionsCountryTopChoices, array_slice($this->getOptionsOfSelectField('#createLocationStep1_country', $crawler), 0, count($expectedOptionsCountryTopChoices), true));

		// invalid country -> step 1 again
		if (method_exists($form, 'disableValidation')) {
			$form->disableValidation();
			$crawler = $this->client->submit($form, array(
				'createLocationStep1[country]' => 'INVALID',
			));
		} else {
			// impossible to send invalid values with DomCrawler, see https://github.com/symfony/symfony/issues/7672
			// TODO remove as soon as Symfony >= 2.4 is required
			$crawler = $this->client->request($form->getMethod(), $form->getUri(), array(
				'flow_createLocation_step' => 1,
				'createLocationStep1' => array(
					'country' => 'INVALID',
				),
			));
		}
		$this->assertCurrentStepNumber(1, $crawler);
		$this->assertFieldHasError('#createLocationStep1_country', 'This value is not valid.', $crawler);

		// country without region -> step 3
		$form = $crawler->selectButton('next')->form();
		$crawler = $this->client->submit($form, array(
			'createLocationStep1[country]' => 'AX',
		));
		$this->assertCurrentStepNumber(3, $crawler);

		$this->assertEquals(array(
			'country' => 'Åland Islands',
		), $this->getListContent('', $crawler));

		$form = $crawler->selectButton('back')->form();

		// go back -> step 1
		$crawler = $this->client->submit($form);
		$this->assertCurrentStepNumber(1, $crawler);

		$form = $crawler->selectButton('next')->form();

		$this->assertEquals('AX', $form->get('createLocationStep1[country]')->getValue());

		// country with region -> step 2
		$crawler = $this->client->submit($form, array(
			'createLocationStep1[country]' => 'DE',
		));
		$this->assertCurrentStepNumber(2, $crawler);

		$form = $crawler->selectButton('next')->form();

		$this->assertEquals('', $form->get('createLocationStep2[region]')->getValue());

		$expectedOptionsRegionTopChoices = array();
		foreach (array('' => '', 'DE-BW' => 'Baden-Württemberg') as $value => $label) {
			$expectedOptionsRegionTopChoices[] = array(
				'value' => $value,
				'label' => $label,
			);
		}
		$actualRegionOptions = $this->getOptionsOfSelectField('#createLocationStep2_region', $crawler);
		$this->assertEquals($expectedOptionsRegionTopChoices, array_slice($actualRegionOptions, 0, count($expectedOptionsRegionTopChoices), true));
		$this->assertCount(1 + count(Regions::getRegionsForCountry('DE')), $actualRegionOptions);

		// invalid region -> step 2 again
		if (method_exists($form, 'disableValidation')) {
			$form->disableValidation();
			$crawler = $this->client->submit($form, array(
				'createLocationStep2[region]' => 'INVALID',
			));
		} else {
			// impossible to send invalid values with DomCrawler, see https://github.com/symfony/symfony/issues/7672
			// TODO remove as soon as Symfony >= 2.4 is required
			$crawler = $this->client->request($form->getMethod(), $form->getUri(), array(
				'flow_createLocation_step' => 2,
				'createLocationStep2' => array(
					'region' => 'INVALID',
				),
			));
		}
		$this->assertCurrentStepNumber(2, $crawler);
		$this->assertFieldHasError('#createLocationStep2_region', 'This value is not valid.', $crawler);

		// region -> step 3
		$form = $crawler->selectButton('next')->form();
		$crawler = $this->client->submit($form, array(
			'createLocationStep2[region]' => 'DE-BE',
		));
		$this->assertCurrentStepNumber(3, $crawler);

		$this->assertEquals(array(
			'country' => 'Germany',
			'region' => 'Berlin',
		), $this->getListContent('', $crawler));

		// finish flow
		$form = $crawler->selectButton('finish')->form();
		$this->client->submit($form);
		$this->assertEquals('_FormFlow_start', $this->client->getRequest()->attributes->get('_route'));
	}

}