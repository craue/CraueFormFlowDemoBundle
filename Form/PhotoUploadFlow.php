<?php

namespace Craue\FormFlowDemoBundle\Form;

use Craue\FormFlowBundle\Form\FormFlow;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2015 Christian Raue
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
		return array(
			array(
				'label' => 'photo_select',
				'type' => new PhotoUploadForm(),
			),
			array(
				'label' => 'photo_comment',
				'type' => new PhotoUploadForm(),
			),
			array(
				'label' => 'confirmation',
				'type' => new PhotoUploadForm(),
			),
		);
	}

}
