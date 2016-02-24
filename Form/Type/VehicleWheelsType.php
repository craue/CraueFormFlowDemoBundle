<?php

namespace Craue\FormFlowDemoBundle\Form\Type;

use Craue\FormFlowDemoBundle\Entity\Vehicle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2016 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class VehicleWheelsType extends AbstractType {

	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$setChoicesAsValuesOption = method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix') && method_exists('Symfony\Component\Form\AbstractType', 'getName'); // Symfony's Form component >=2.8 && <3.0
		$validValues = Vehicle::getValidWheels();

		$defaultOptions = array(
			'choices' => array_combine($validValues, $validValues),
			'placeholder' => '',
		);

		if ($setChoicesAsValuesOption) {
			$defaultOptions['choices_as_values'] = true;
		}

		$resolver->setDefaults($defaultOptions);
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
