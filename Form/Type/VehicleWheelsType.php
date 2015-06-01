<?php

namespace Craue\FormFlowDemoBundle\Form\Type;

use Craue\FormFlowDemoBundle\Entity\Vehicle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2015 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class VehicleWheelsType extends AbstractType {

	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$validValues = Vehicle::getValidWheels();

		$resolver->setDefaults(array(
			'choices' => array_combine($validValues, $validValues),
			'empty_value' => '',
		));
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
		return 'choice';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return 'form_type_vehicleWheels';
	}

}
