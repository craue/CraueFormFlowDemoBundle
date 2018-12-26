<?php

namespace Craue\FormFlowDemoBundle\Form;

use Craue\FormFlowBundle\Event\PostBindSavedDataEvent;
use Craue\FormFlowBundle\Form\FormFlow;
use Craue\FormFlowBundle\Form\FormFlowEvents;
use Craue\FormFlowBundle\Form\FormFlowInterface;
use Craue\FormFlowDemoBundle\Entity\Driver;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * This flow uses one form type for the entire flow.
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2018 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CreateVehicleFlow extends FormFlow implements EventSubscriberInterface {

	/**
	 * {@inheritDoc}
	 */
	public function setEventDispatcher(EventDispatcherInterface $dispatcher) {
		parent::setEventDispatcher($dispatcher);
		$dispatcher->addSubscriber($this);
	}

	/**
	 * {@inheritDoc}
	 */
	public static function getSubscribedEvents() {
		return [
			FormFlowEvents::POST_BIND_SAVED_DATA => 'onPostBindSavedData',
		];
	}

	public function onPostBindSavedData(PostBindSavedDataEvent $event) {
		if ($event->getStepNumber() === 3) {
			$formData = $event->getFormData();

			if ($formData->addDriver) {
				$formData->driver = new Driver();
				$formData->driver->vehicles->add($formData->vehicle);
			}
		}
	}

	/**
	 * {@inheritDoc}
	 */
	protected function loadStepsConfig() {
		$formType = CreateVehicleForm::class;

		return [
			[
				'label' => 'wheels',
				'form_type' => $formType,
			],
			[
				'label' => 'engine',
				'form_type' => $formType,
				'skip' => function($estimatedCurrentStepNumber, FormFlowInterface $flow) {
					return $estimatedCurrentStepNumber > 1 && !$flow->getFormData()->vehicle->canHaveEngine();
				},
			],
			[
				'label' => 'driver',
				'form_type' => $formType,
			],
			[
				'label' => 'driverDetails',
				'form_type' => $formType,
				'skip' => function($estimatedCurrentStepNumber, FormFlowInterface $flow) {
					return $estimatedCurrentStepNumber > 3 && !$flow->getFormData()->addDriver;
				},
			],
			[
				'label' => 'confirmation',
			],
		];
	}

}
