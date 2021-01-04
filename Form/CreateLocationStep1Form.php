<?php

namespace Craue\FormFlowDemoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2021 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CreateLocationStep1Form extends AbstractType {

	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('country', CountryType::class, [
			'placeholder' => '',
			'preferred_choices' => [
				'AT', 'DE', 'CH', 'US',
			],
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getBlockPrefix() {
		return 'createLocationStep1';
	}

}
