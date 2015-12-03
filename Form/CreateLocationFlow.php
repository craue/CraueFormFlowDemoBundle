<?php

namespace Craue\FormFlowDemoBundle\Form;

use Craue\FormFlowBundle\Form\FormFlow;
use Craue\FormFlowBundle\Form\FormFlowInterface;

/**
 * This flow uses one form type per step.
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2015 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CreateLocationFlow extends FormFlow {

	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return 'createLocation';
	}

	/**
	 * {@inheritDoc}
	 */
	protected function loadStepsConfig() {
		$useFqcn = method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix'); // Symfony's Form component >=2.8

		return array(
			array(
				'label' => 'country',
				'form_type' => $useFqcn ? 'Craue\FormFlowDemoBundle\Form\CreateLocationStep1Form' : new CreateLocationStep1Form(),
			),
			array(
				'label' => 'region',
				'form_type' => $useFqcn ? 'Craue\FormFlowDemoBundle\Form\CreateLocationStep2Form' : new CreateLocationStep2Form(),
				'skip' => function($estimatedCurrentStepNumber, FormFlowInterface $flow) {
					return $estimatedCurrentStepNumber > 1 && !$flow->getFormData()->canHaveRegion();
				},
			),
			array(
				'label' => 'confirmation',
			),
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFormOptions($step, array $options = array()) {
		$options = parent::getFormOptions($step, $options);

		if ($step === 2) {
			$options['country'] = $this->getFormData()->country;
		}

		return $options;
	}

}
