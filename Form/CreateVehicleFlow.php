<?php

namespace Craue\FormFlowDemoBundle\Form;

use Craue\FormFlowBundle\Form\FormFlow;
use Craue\FormFlowBundle\Form\FormFlowInterface;
use Symfony\Component\Form\FormTypeInterface;

/**
 * This flow uses one form type for the entire flow.
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2014 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CreateVehicleFlow extends FormFlow {

	/**
	 * @var FormTypeInterface
	 */
	protected $formType;

	public function setFormType(FormTypeInterface $formType) {
		$this->formType = $formType;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return 'createVehicle';
	}

	/**
	 * {@inheritDoc}
	 */
	protected function loadStepsConfig() {
		return array(
			array(
				'label' => 'wheels',
				'type' => $this->formType,
			),
			array(
				'label' => 'engine',
				'type' => $this->formType,
				'skip' => function($estimatedCurrentStepNumber, FormFlowInterface $flow) {
					return $estimatedCurrentStepNumber > 1 && !$flow->getFormData()->canHaveEngine();
				},
			),
			array(
				'label' => 'confirmation',
				'type' => $this->formType, // needed to avoid InvalidOptionsException regarding option 'flowStep'
			),
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFormOptions($step, array $options = array()) {
		$options = parent::getFormOptions($step, $options);

		$options['cascade_validation'] = true;
		$options['flowStep'] = $step;

		return $options;
	}

}
