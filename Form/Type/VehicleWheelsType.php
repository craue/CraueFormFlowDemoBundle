<?php

namespace Craue\FormFlowDemoBundle\Form\Type;

use Craue\FormFlowDemoBundle\Entity\Vehicle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2018 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class VehicleWheelsType extends AbstractType {

	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$useFqcn = method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix'); // Symfony's Form component >=2.8
		$usePlaceholder = method_exists('Symfony\Component\Form\AbstractType', 'configureOptions'); // Symfony's Form component 2.6 deprecated the "empty_value" option, but there seems to be no way to detect that version, so stick to this >=2.7 check.
		$validValues = Vehicle::getValidWheels();

		$defaultOptions = array(
			'choices' => array_combine($validValues, $validValues),
			$usePlaceholder ? 'placeholder' : 'empty_value' => '',
		);

		if ($useFqcn) {
			$defaultOptions['choices_as_values'] = true;
		}

		$resolver->setDefaults($defaultOptions);
	}

	/**
	 * {@inheritDoc}
	 */
	// TODO remove as soon as Symfony >= 2.7 is required
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$this->configureOptions($resolver);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParent() {
		$useFqcn = method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix'); // Symfony's Form component >=2.8

		return $useFqcn ? 'Symfony\Component\Form\Extension\Core\Type\ChoiceType' : 'choice';
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
		return 'form_type_vehicleWheels';
	}

}
