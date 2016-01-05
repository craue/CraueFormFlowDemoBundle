<?php

namespace Craue\FormFlowDemoBundle\Form;

use Craue\FormFlowBundle\Form\FormFlow;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2016 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class PhotoUploadFlow extends FormFlow {

	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return 'photoUpload';
	}

	/**
	 * {@inheritDoc}
	 */
	protected function loadStepsConfig() {
		$useFqcn = method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix'); // Symfony's Form component >=2.8
		$formType = $useFqcn ? 'Craue\FormFlowDemoBundle\Form\PhotoUploadForm' : new PhotoUploadForm();

		return array(
			array(
				'label' => 'photo_select',
				'form_type' => $formType,
			),
			array(
				'label' => 'photo_comment',
				'form_type' => $formType,
			),
			array(
				'label' => 'confirmation',
				'form_type' => $formType,
			),
		);
	}

}
