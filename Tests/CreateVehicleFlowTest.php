<?php

namespace Craue\FormFlowDemoBundle\Tests;

use Craue\FormFlowDemoBundle\Entity\Vehicle;

/**
 * @group integration
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2021 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CreateVehicleFlowTest extends IntegrationTestCase {

	public function testCreateVehicle() {
		static::$client->followRedirects();
		$crawler = static::$client->request('GET', $this->url('_FormFlow_createVehicle'));

		$this->assertSame(200, static::$client->getResponse()->getStatusCode());
		$this->assertCurrentStepNumber(1, $crawler);

		$form = $crawler->selectButton('next')->form();

		$this->assertEquals('', $form->get('createVehicle[vehicle][numberOfWheels]')->getValue());

		$expectedOptionsNumberOfWheels = [
			[
				'value' => '',
				'label' => '',
			]
		];
		foreach (Vehicle::getValidWheels() as $wheels) {
			$expectedOptionsNumberOfWheels[] = [
				'value' => $wheels,
				'label' => $wheels,
			];
		}
		$this->assertEquals($expectedOptionsNumberOfWheels, $this->getOptionsOfSelectField('#createVehicle_vehicle_numberOfWheels', $crawler));

		// invalid number of wheels -> step 1 again
		$form->disableValidation();
		$crawler = static::$client->submit($form, [
			'createVehicle[vehicle][numberOfWheels]' => 99,
		]);
		$this->assertCurrentStepNumber(1, $crawler);
		$this->assertFieldHasError('#createVehicle_vehicle_numberOfWheels', 'This value is not valid.', $crawler);

		// 2 wheels -> step 3
		$form = $crawler->selectButton('next')->form();
		$crawler = static::$client->submit($form, [
			'createVehicle[vehicle][numberOfWheels]' => 2,
		]);
		$this->assertCurrentStepNumber(3, $crawler);

		$form = $crawler->selectButton('back')->form();

		$this->assertEquals('1', $form->get('createVehicle[addDriver]')->getValue());

		// go back -> step 1
		$crawler = static::$client->submit($form);
		$this->assertCurrentStepNumber(1, $crawler);

		$form = $crawler->selectButton('next')->form();

		$this->assertEquals('2', $form->get('createVehicle[vehicle][numberOfWheels]')->getValue());

		// 4 wheels -> step 2
		$crawler = static::$client->submit($form, [
			'createVehicle[vehicle][numberOfWheels]' => 4,
		]);
		$this->assertCurrentStepNumber(2, $crawler);

		$form = $crawler->selectButton('next')->form();

		$this->assertEquals('', $form->get('createVehicle[vehicle][engine]')->getValue());

		$expectedOptionsEngine = [
			[
				'value' => '',
				'label' => '',
			]
		];
		$validEngines = Vehicle::getValidEngines();
		sort($validEngines);
		foreach ($validEngines as $engine) {
			$expectedOptionsEngine[] = [
				'value' => $engine,
				'label' => $this->trans($engine, [], 'vehicleEngines'),
			];
		}
		$this->assertEquals($expectedOptionsEngine, $this->getOptionsOfSelectField('#createVehicle_vehicle_engine', $crawler));

		// invalid engine -> step 2 again
		$form->disableValidation();
		$crawler = static::$client->submit($form, [
			'createVehicle[vehicle][engine]' => 'magic',
		]);
		$this->assertCurrentStepNumber(2, $crawler);
		$this->assertFieldHasError('#createVehicle_vehicle_engine', 'This value is not valid.', $crawler);

		// engine -> step 3
		$form = $crawler->selectButton('next')->form();
		$crawler = static::$client->submit($form, [
			'createVehicle[vehicle][engine]' => 'GAS',
		]);
		$this->assertCurrentStepNumber(3, $crawler);

		$form = $crawler->selectButton('next')->form();

		$this->assertEquals('1', $form->get('createVehicle[addDriver]')->getValue());

		// no driver -> step 5
		$form->get('createVehicle[addDriver]')->untick();
		$crawler = static::$client->submit($form);
		$this->assertCurrentStepNumber(5, $crawler);

		$form = $crawler->selectButton('back')->form();

		$this->assertEquals([
			'wheels' => '4',
			'engine' => 'gas engine',
		], $this->getListContent('', $crawler));

		// go back -> step 3
		$crawler = static::$client->submit($form);
		$this->assertCurrentStepNumber(3, $crawler);

		$form = $crawler->selectButton('next')->form();

		$this->assertNull($form->get('createVehicle[addDriver]')->getValue());

		// driver -> step 4
		$form->get('createVehicle[addDriver]')->tick();
		$crawler = static::$client->submit($form);
		$this->assertCurrentStepNumber(4, $crawler);

		$form = $crawler->selectButton('next')->form();

		$this->assertEquals('', $form->get('createVehicle[driver][firstname]')->getValue());
		$this->assertEquals('', $form->get('createVehicle[driver][lastname]')->getValue());

		// empty fields -> step 4 again
		$crawler = static::$client->submit($form, [
			'createVehicle[driver][firstname]' => '',
			'createVehicle[driver][lastname]' => '',
		]);
		$this->assertCurrentStepNumber(4, $crawler);
		$this->assertFieldHasError('#createVehicle_driver_firstname', 'This value should not be blank.', $crawler);
		$this->assertFieldHasError('#createVehicle_driver_lastname', 'This value should not be blank.', $crawler);

		// driver details -> step 5
		$form = $crawler->selectButton('next')->form();
		$crawler = static::$client->submit($form, [
			'createVehicle[driver][firstname]' => 'First',
			'createVehicle[driver][lastname]' => 'Last',
		]);
		$this->assertCurrentStepNumber(5, $crawler);

		// finish flow
		$form = $crawler->selectButton('finish')->form();
		static::$client->submit($form);
		$this->assertEquals('_FormFlow_start', static::$client->getRequest()->attributes->get('_route'));
	}

}
