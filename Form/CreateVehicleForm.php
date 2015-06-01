<?php

namespace Craue\FormFlowDemoBundle\Form;

use Craue\FormFlowDemoBundle\Entity\Vehicle;
use Craue\FormFlowDemoBundle\Form\CreateVehicle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2015 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CreateVehicleForm extends AbstractType {

	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		switch ($options['flow_step']) {
			case 1:
				$vehicleForm = $builder->create('vehicle', 'form', array(
					'data_class' => get_class(new Vehicle()),
				));
				$vehicleForm->add('numberOfWheels', 'form_type_vehicleWheels');
				$builder->add($vehicleForm);
				break;
			case 2:
				$vehicleForm = $builder->create('vehicle', 'form', array(
					'data_class' => get_class(new Vehicle()),
				));
				$vehicleForm->add('engine', 'form_type_vehicleEngine');
				$builder->add($vehicleForm);
				break;
			case 3:
				$builder->add('addDriver', null, array(
					'required' => false,
				));
				break;
			case 4:
				$builder->add('driver', 'form_type_driver');
				break;
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => get_class(new CreateVehicle()),
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
	public function getName() {
		return 'createVehicle';
	}

}
