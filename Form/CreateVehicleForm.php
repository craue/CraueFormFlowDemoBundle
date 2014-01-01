<?php

namespace Craue\FormFlowDemoBundle\Form;

use Craue\FormFlowDemoBundle\Entity\Vehicle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2014 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CreateVehicleForm extends AbstractType {

	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		switch ($options['flowStep']) {
			case 1:
				$validValues = Vehicle::getValidWheels();
				$builder->add('numberOfWheels', 'choice', array(
					'choices' => array_combine($validValues, $validValues),
					'empty_value' => '',
				));
				break;
			case 2:
				$builder->add('engine', 'form_type_vehicleEngine', array(
					'empty_value' => '',
				));
				break;
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
			'flowStep' => null,
		));
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return 'createVehicle';
	}

}
