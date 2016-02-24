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
class CreateLocationStep2Form extends AbstractType {

	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$useFqcn = method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix'); // Symfony's Form component >=2.8

		$builder->add('region', $useFqcn ? 'Craue\FormFlowDemoBundle\Form\Type\LocationRegionType' : 'form_type_locationRegion', array(
			'country' => $options['country'],
			'placeholder' => '',
			'required' => true,
		));
	}

	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'country' => null,
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
		return 'createLocationStep2';
	}

}
