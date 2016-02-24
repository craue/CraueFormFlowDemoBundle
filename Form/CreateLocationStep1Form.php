<?php

namespace Craue\FormFlowDemoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2016 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CreateLocationStep1Form extends AbstractType {

	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$useFqcn = method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix'); // Symfony's Form component >=2.8
		$setChoicesAsValuesOption = $useFqcn && method_exists('Symfony\Component\Form\AbstractType', 'getName'); // Symfony's Form component >=2.8 && <3.0

		$defaultChoiceOptions = array();
		if ($setChoicesAsValuesOption) {
			$defaultChoiceOptions['choices_as_values'] = true;
		}

		$builder->add('country', $useFqcn ? 'Symfony\Component\Form\Extension\Core\Type\CountryType' : 'country', array_merge($defaultChoiceOptions, array(
			'placeholder' => '',
			'preferred_choices' => array(
				'AT', 'CH', 'DE', 'US',
			),
		)));
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return $this->getBlockPrefix();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getBlockPrefix() {
		return 'createLocationStep1';
	}

}
