<?php

namespace Craue\FormFlowDemoBundle\Controller;

use Craue\FormFlowBundle\Form\FormFlowInterface;
use Craue\FormFlowBundle\Util\FormFlowUtil;
use Craue\FormFlowDemoBundle\Entity\Location;
use Craue\FormFlowDemoBundle\Entity\PhotoUpload;
use Craue\FormFlowDemoBundle\Entity\Topic;
use Craue\FormFlowDemoBundle\Form\CreateLocationFlow;
use Craue\FormFlowDemoBundle\Form\CreateTopicFlow;
use Craue\FormFlowDemoBundle\Form\CreateVehicle;
use Craue\FormFlowDemoBundle\Form\CreateVehicleFlow;
use Craue\FormFlowDemoBundle\Form\PhotoUploadFlow;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
 * @Route("/CraueFormFlow")
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2022 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class FormFlowDemoController extends AbstractController {

	/**
	 * @var FormFlowUtil
	 */
	private $formFlowUtil;

	/**
	 * @var Environment
	 */
	private $twig;

	public function __construct(FormFlowUtil $formFlowUtil, Environment $twig) {
		$this->formFlowUtil = $formFlowUtil;
		$this->twig = $twig;
	}

	/**
	 * @Route("/", name="_FormFlow_start")
	 */
	public function indexAction() {
		return new Response($this->twig->render('@CraueFormFlowDemo/FormFlowDemo/index.html.twig'));
	}

	/**
	 * @Route("/create-location/", name="_FormFlow_createLocation")
	 */
	public function createLocationAction(Request $request, CreateLocationFlow $flow) {
		return $this->processFlow($request, new Location(), $flow,
				'@CraueFormFlowDemo/FormFlowDemo/createLocation.html.twig');
	}

	/**
	 * @Route("/create-vehicle/", name="_FormFlow_createVehicle")
	 */
	public function createVehicleAction(Request $request, CreateVehicleFlow $flow) {
		return $this->processFlow($request, new CreateVehicle(), $flow,
				'@CraueFormFlowDemo/FormFlowDemo/createVehicle.html.twig');
	}

	/**
	 * @Route("/create-topic/", name="_FormFlow_createTopic")
	 */
	public function createTopicAction(Request $request, CreateTopicFlow $flow) {
		return $this->processFlow($request, new Topic(), $flow,
				'@CraueFormFlowDemo/FormFlowDemo/createTopic.html.twig');
	}

	/**
	 * @Route("/create-topic-dsn/", name="_FormFlow_createTopic_dynamicStepNavigation")
	 */
	public function createTopicWithDynamicStepNavigationAction(Request $request, CreateTopicFlow $flow) {
		$flow->setAllowDynamicStepNavigation(true);

		return $this->processFlow($request, new Topic(), $flow,
				'@CraueFormFlowDemo/FormFlowDemo/createTopicWithDynamicStepNavigation.html.twig');
	}

	/**
	 * @Route("/create-topic-redirect-after-submit/", name="_FormFlow_createTopic_redirectAfterSubmit")
	 */
	public function createTopicWithRedirectAfterSubmitAction(Request $request, CreateTopicFlow $flow) {
		$flow->setAllowRedirectAfterSubmit(true);

		return $this->processFlow($request, new Topic(), $flow,
				'@CraueFormFlowDemo/FormFlowDemo/createTopicWithRedirectAfterSubmit.html.twig');
	}

	/**
	 * @Route("/photo-upload/", name="_FormFlow_photoUpload")
	 */
	public function photoUploadAction(Request $request, PhotoUploadFlow $flow) {
		return $this->processFlow($request, new PhotoUpload(), $flow,
				'@CraueFormFlowDemo/FormFlowDemo/photoUpload.html.twig');
	}

	protected function processFlow(Request $request, $formData, FormFlowInterface $flow, $template) {
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
			$params = $this->formFlowUtil->addRouteParameters(array_merge($request->query->all(),
					$request->attributes->get('_route_params')), $flow);

			return $this->redirect($this->generateUrl($request->attributes->get('_route'), $params));
		}

		return new Response($this->twig->render($template, [
			'form' => $form->createView(),
			'flow' => $flow,
			'formData' => $formData,
		]));
	}

}
