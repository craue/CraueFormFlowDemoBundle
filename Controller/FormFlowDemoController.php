<?php

namespace Craue\FormFlowDemoBundle\Controller;

use Craue\FormFlowBundle\Form\FormFlowInterface;
use Craue\FormFlowDemoBundle\Entity\Location;
use Craue\FormFlowDemoBundle\Entity\Topic;
use Craue\FormFlowDemoBundle\Form\CreateVehicle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/CraueFormFlow")
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2013 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class FormFlowDemoController extends Controller {

	/**
	 * @Route("/", name="_FormFlow_start")
	 * @Template
	 */
	public function indexAction() {
		return array();
	}

	/**
	 * @Route("/create-location/", name="_FormFlow_createLocation")
	 * @Template
	 */
	public function createLocationAction() {
		return $this->processFlow(new Location(), $this->get('craueFormFlowDemoBundle.form.flow.createLocation'));
	}

	/**
	 * @Route("/create-vehicle/", name="_FormFlow_createVehicle")
	 * @Template
	 */
	public function createVehicleAction() {
		return $this->processFlow(new CreateVehicle(), $this->get('craueFormFlowDemoBundle.form.flow.createVehicle'));
	}

	/**
	 * @Route("/create-topic/", name="_FormFlow_createTopic")
	 * @Template
	 */
	public function createTopicAction() {
		return $this->processFlow(new Topic(), $this->getCreateTopicFlow(false));
	}

	/**
	 * @Route("/create-topic-dsn/start/", name="_FormFlow_createTopic_dynamicStepNavigation_start")
	 */
	public function createTopicWithDynamicStepNavigationStartAction() {
		$flow = $this->getCreateTopicFlow(true);
		$flow->reset();

		return $this->redirect($this->generateUrl('_FormFlow_createTopic_dynamicStepNavigation'));
	}

	/**
	 * @Route("/create-topic-dsn/", name="_FormFlow_createTopic_dynamicStepNavigation")
	 * @Template
	 */
	public function createTopicWithDynamicStepNavigationAction() {
		return $this->processFlow(new Topic(), $this->getCreateTopicFlow(true));
	}

	protected function getCreateTopicFlow($dynamicStepNavigationEnabled) {
		$flow = $this->get('craueFormFlowDemoBundle.form.flow.createTopic');
		$flow->setAllowDynamicStepNavigation($dynamicStepNavigationEnabled);

		// separate data per mode
		if ($dynamicStepNavigationEnabled) {
			$flow->setStepDataKey($flow->getStepDataKey() . '_dsn');
		}

		return $flow;
	}

	protected function processFlow($formData, FormFlowInterface $flow) {
		$flow->bind($formData);

		$form = $flow->createForm();
		if ($flow->isValid($form)) {
			$flow->saveCurrentStepData($form);

			if ($flow->nextStep()) {
				// create form for next step
				$form = $flow->createForm();
			} else {
				// flow finished
				// ...

				$flow->reset();

				return $this->redirect($this->generateUrl('_FormFlow_start'));
			}
		}

		return array(
			'form' => $form->createView(),
			'flow' => $flow,
			'formData' => $formData,
		);
	}

}
