<?php

namespace Craue\FormFlowDemoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2015 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CreateLocationStep1Form extends AbstractType {

	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('country', 'country', array(
			'empty_value' => '',
			'preferred_choices' => array(
				'AT', 'CH', 'DE', 'US',
			),
		));
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return 'createLocationStep1';
	}

}
