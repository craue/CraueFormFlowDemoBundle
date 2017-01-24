<?php

namespace Craue\FormFlowDemoBundle\Controller;

use Craue\FormFlowBundle\Form\FormFlowInterface;
use Craue\FormFlowDemoBundle\Entity\Location;
use Craue\FormFlowDemoBundle\Entity\PhotoUpload;
use Craue\FormFlowDemoBundle\Entity\Topic;
use Craue\FormFlowDemoBundle\Form\CreateVehicle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/CraueFormFlow")
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2017 Christian Raue
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
		return $this->processFlow(new Topic(), $this->get('craueFormFlowDemoBundle.form.flow.createTopic'));
	}

	/**
	 * @Route("/create-topic-dsn/", name="_FormFlow_createTopic_dynamicStepNavigation")
	 * @Template
	 */
	public function createTopicWithDynamicStepNavigationAction() {
		$flow = $this->get('craueFormFlowDemoBundle.form.flow.createTopic');
		$flow->setAllowDynamicStepNavigation(true);

		return $this->processFlow(new Topic(), $flow);
	}

	/**
	 * @Route("/create-topic-redirect-after-submit/", name="_FormFlow_createTopic_redirectAfterSubmit")
	 * @Template
	 */
	public function createTopicWithRedirectAfterSubmitAction() {
		$flow = $this->get('craueFormFlowDemoBundle.form.flow.createTopic');
		$flow->setAllowRedirectAfterSubmit(true);

		return $this->processFlow(new Topic(), $flow);
	}

	/**
	 * @Route("/photo-upload/", name="_FormFlow_photoUpload")
	 * @Template
	 */
	public function photoUploadAction() {
		return $this->processFlow(new PhotoUpload(), $this->get('craueFormFlowDemoBundle.form.flow.photoUpload'));
	}

	protected function processFlow($formData, FormFlowInterface $flow) {
		$flow->bind($formData);

		$form = $submittedForm = $flow->createForm();
		if ($flow->isValid($submittedForm)) {
			$flow->saveCurrentStepData($submittedForm);

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

		if ($flow->redirectAfterSubmit($submittedForm)) {
			$request = $this->get('request_stack')->getCurrentRequest();
			$params = $this->get('craue_formflow_util')->addRouteParameters(array_merge($request->query->all(),
					$request->attributes->get('_route_params')), $flow);

			return $this->redirect($this->generateUrl($request->attributes->get('_route'), $params));
		}

		return array(
			'form' => $form->createView(),
			'flow' => $flow,
			'formData' => $formData,
		);
	}

}
