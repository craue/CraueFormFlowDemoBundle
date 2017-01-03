<?php

namespace Craue\FormFlowDemoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2017 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CreateLocationStep1Form extends AbstractType {

	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$useFqcn = method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix'); // Symfony's Form component >=2.8
		$usePlaceholder = method_exists('Symfony\Component\Form\AbstractType', 'configureOptions'); // Symfony's Form component 2.6 deprecated the "empty_value" option, but there seems to be no way to detect that version, so stick to this >=2.7 check.
		$useChoicesAsValues = $useFqcn;

		$defaultChoiceOptions = array();
		if ($useChoicesAsValues) {
			$defaultChoiceOptions['choices_as_values'] = true;
		}

		$builder->add('country', $useFqcn ? 'Symfony\Component\Form\Extension\Core\Type\CountryType' : 'country', array_merge($defaultChoiceOptions, array(
			$usePlaceholder ? 'placeholder' : 'empty_value' => '',
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
