<?php

namespace Craue\FormFlowDemoBundle\Controller;

use Craue\FormFlowBundle\Form\FormFlowInterface;
use Craue\FormFlowDemoBundle\Entity\Location;
use Craue\FormFlowDemoBundle\Entity\PhotoUpload;
use Craue\FormFlowDemoBundle\Entity\Topic;
use Craue\FormFlowDemoBundle\Form\CreateLocationFlow;
use Craue\FormFlowDemoBundle\Form\CreateTopicFlow;
use Craue\FormFlowDemoBundle\Form\CreateVehicle;
use Craue\FormFlowDemoBundle\Form\CreateVehicleFlow;
use Craue\FormFlowDemoBundle\Form\PhotoUploadFlow;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/CraueFormFlow")
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2020 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class FormFlowDemoController extends AbstractController {

	/**
	 * @Route("/", name="_FormFlow_start")
	 */
	public function indexAction() {
		return $this->render('@CraueFormFlowDemo/FormFlowDemo/index.html.twig');
	}

	/**
	 * @Route("/create-location/", name="_FormFlow_createLocation")
	 */
	public function createLocationAction(CreateLocationFlow $flow) {
		return $this->processFlow(new Location(), $flow,
				'@CraueFormFlowDemo/FormFlowDemo/createLocation.html.twig');
	}

	/**
	 * @Route("/create-vehicle/", name="_FormFlow_createVehicle")
	 */
	public function createVehicleAction(CreateVehicleFlow $flow) {
		return $this->processFlow(new CreateVehicle(), $flow,
				'@CraueFormFlowDemo/FormFlowDemo/createVehicle.html.twig');
	}

	/**
	 * @Route("/create-topic/", name="_FormFlow_createTopic")
	 */
	public function createTopicAction(CreateTopicFlow $flow) {
		return $this->processFlow(new Topic(), $flow,
				'@CraueFormFlowDemo/FormFlowDemo/createTopic.html.twig');
	}

	/**
	 * @Route("/create-topic-dsn/", name="_FormFlow_createTopic_dynamicStepNavigation")
	 */
	public function createTopicWithDynamicStepNavigationAction(CreateTopicFlow $flow) {
		$flow->setAllowDynamicStepNavigation(true);

		return $this->processFlow(new Topic(), $flow,
				'@CraueFormFlowDemo/FormFlowDemo/createTopicWithDynamicStepNavigation.html.twig');
	}

	/**
	 * @Route("/create-topic-redirect-after-submit/", name="_FormFlow_createTopic_redirectAfterSubmit")
	 */
	public function createTopicWithRedirectAfterSubmitAction(CreateTopicFlow $flow) {
		$flow->setAllowRedirectAfterSubmit(true);

		return $this->processFlow(new Topic(), $flow,
				'@CraueFormFlowDemo/FormFlowDemo/createTopicWithRedirectAfterSubmit.html.twig');
	}

	/**
	 * @Route("/photo-upload/", name="_FormFlow_photoUpload")
	 */
	public function photoUploadAction(PhotoUploadFlow $flow) {
		return $this->processFlow(new PhotoUpload(), $flow,
				'@CraueFormFlowDemo/FormFlowDemo/photoUpload.html.twig');
	}

	protected function processFlow($formData, FormFlowInterface $flow, $template) {
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

		return $this->render($template, [
			'form' => $form->createView(),
			'flow' => $flow,
			'formData' => $formData,
		]);
	}

}
