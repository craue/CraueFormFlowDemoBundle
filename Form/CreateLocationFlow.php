<?php

namespace Craue\FormFlowDemoBundle\Form;

use Craue\FormFlowBundle\Form\FormFlow;
use Craue\FormFlowBundle\Form\FormFlowInterface;

/**
 * This flow uses one form type per step.
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2021 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CreateLocationFlow extends FormFlow {

	/**
	 * {@inheritDoc}
	 */
	protected function loadStepsConfig() {
		return [
			[
				'label' => 'country',
				'form_type' => CreateLocationStep1Form::class,
			],
			[
				'label' => 'region',
				'form_type' => CreateLocationStep2Form::class,
				'skip' => function($estimatedCurrentStepNumber, FormFlowInterface $flow) {
					return $estimatedCurrentStepNumber > 1 && !$flow->getFormData()->canHaveRegion();
				},
			],
			[
				'label' => 'confirmation',
			],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFormOptions($step, array $options = []) {
		$options = parent::getFormOptions($step, $options);

		if ($step === 2) {
			$options['country'] = $this->getFormData()->country;
		}

		return $options;
	}

}
