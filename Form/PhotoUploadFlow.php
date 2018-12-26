<?php

namespace Craue\FormFlowDemoBundle\Form;

use Craue\FormFlowBundle\Form\FormFlow;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2018 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class PhotoUploadFlow extends FormFlow {

	/**
	 * {@inheritDoc}
	 */
	protected function loadStepsConfig() {
		$formType = PhotoUploadForm::class;

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
