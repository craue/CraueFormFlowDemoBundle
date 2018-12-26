<?php

namespace Craue\FormFlowDemoBundle\Form;

use Craue\FormFlowDemoBundle\Entity\Vehicle;
use Craue\FormFlowDemoBundle\Form\Type\DriverType;
use Craue\FormFlowDemoBundle\Form\Type\VehicleEngineType;
use Craue\FormFlowDemoBundle\Form\Type\VehicleWheelsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2018 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CreateVehicleForm extends AbstractType {

	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		switch ($options['flow_step']) {
			case 1:
				$vehicleForm = $builder->create('vehicle', FormType::class, [
					'data_class' => Vehicle::class,
				]);
				$vehicleForm->add('numberOfWheels', VehicleWheelsType::class);
				$builder->add($vehicleForm);
				break;
			case 2:
				$vehicleForm = $builder->create('vehicle', FormType::class, [
					'data_class' => Vehicle::class,
				]);
				$vehicleForm->add('engine', VehicleEngineType::class);
				$builder->add($vehicleForm);
				break;
			case 3:
				$builder->add('addDriver', null, [
					'required' => false,
				]);
				break;
			case 4:
				$builder->add('driver', DriverType::class);
				break;
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			'data_class' => CreateVehicle::class,
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getBlockPrefix() {
		return 'createVehicle';
	}

}
