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
	public function getName() {
		return 'createVehicle';
	}

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
		return array(
			FormFlowEvents::POST_BIND_SAVED_DATA => 'onPostBindSavedData',
		);
	}

	public function onPostBindSavedData(PostBindSavedDataEvent $event) {
		if ($event->getStep() === 3) {
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
		$useFqcn = method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix'); // Symfony's Form component >=2.8
		$formType = $useFqcn ? 'Craue\FormFlowDemoBundle\Form\CreateVehicleForm' : 'createVehicle';

		return array(
			array(
				'label' => 'wheels',
				'type' => $formType,
			),
			array(
				'label' => 'engine',
				'type' => $formType,
				'skip' => function($estimatedCurrentStepNumber, FormFlowInterface $flow) {
					return $estimatedCurrentStepNumber > 1 && !$flow->getFormData()->vehicle->canHaveEngine();
				},
			),
			array(
				'label' => 'driver',
				'type' => $formType,
			),
			array(
				'label' => 'driverDetails',
				'type' => $formType,
				'skip' => function($estimatedCurrentStepNumber, FormFlowInterface $flow) {
					return $estimatedCurrentStepNumber > 3 && !$flow->getFormData()->addDriver;
				},
			),
			array(
				'label' => 'confirmation',
			),
		);
	}

}
