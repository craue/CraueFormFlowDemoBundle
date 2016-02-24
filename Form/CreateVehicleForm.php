<?php

namespace Craue\FormFlowDemoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2016 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CreateVehicleForm extends AbstractType {

	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$useFqcn = method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix'); // Symfony's Form component >=2.8

		switch ($options['flow_step']) {
			case 1:
				$vehicleForm = $builder->create('vehicle', $useFqcn ? 'Symfony\Component\Form\Extension\Core\Type\FormType' : 'form', array(
					'data_class' => 'Craue\FormFlowDemoBundle\Entity\Vehicle',
				));
				$vehicleForm->add('numberOfWheels', $useFqcn ? 'Craue\FormFlowDemoBundle\Form\Type\VehicleWheelsType' : 'form_type_vehicleWheels');
				$builder->add($vehicleForm);
				break;
			case 2:
				$vehicleForm = $builder->create('vehicle', $useFqcn ? 'Symfony\Component\Form\Extension\Core\Type\FormType' : 'form', array(
					'data_class' => 'Craue\FormFlowDemoBundle\Entity\Vehicle',
				));
				$vehicleForm->add('engine', $useFqcn ? 'Craue\FormFlowDemoBundle\Form\Type\VehicleEngineType' : 'form_type_vehicleEngine');
				$builder->add($vehicleForm);
				break;
			case 3:
				$builder->add('addDriver', null, array(
					'required' => false,
				));
				break;
			case 4:
				$builder->add('driver', $useFqcn ? 'Craue\FormFlowDemoBundle\Form\Type\DriverType' : 'form_type_driver');
				break;
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => 'Craue\FormFlowDemoBundle\Form\CreateVehicle',
		));
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
		return 'createVehicle';
	}

}
