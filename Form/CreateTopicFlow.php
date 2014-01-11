<?php

namespace Craue\FormFlowDemoBundle\Form;

use Craue\FormFlowBundle\Form\FormFlow;
use Craue\FormFlowBundle\Form\FormFlowInterface;

/**
 * This flow uses one form type for the entire flow.
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2014 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CreateTopicFlow extends FormFlow {

	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return 'createTopic';
	}

	/**
	 * {@inheritDoc}
	 */
	protected function loadStepsConfig() {
		return array(
			array(
				'label' => 'basics',
				'type' => new CreateTopicForm(),
			),
			array(
				'label' => 'comment',
				'type' => new CreateTopicForm(),
			),
			array(
				'label' => 'bug_details',
				'type' => new CreateTopicForm(),
				'skip' => function($estimatedCurrentStepNumber, FormFlowInterface $flow) {
					return $estimatedCurrentStepNumber > 1 && !$flow->getFormData()->isBugReport();
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

		$options['cascade_validation'] = true;

		if ($step === 3) {
			$options['isBugReport'] = $this->getFormData()->isBugReport();
		}

		return $options;
	}

}
