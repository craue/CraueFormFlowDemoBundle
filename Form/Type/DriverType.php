<?php

namespace Craue\FormFlowDemoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2017 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class DriverType extends AbstractType {

	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('firstname');
		$builder->add('lastname');
	}

	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => 'Craue\FormFlowDemoBundle\Entity\Driver',
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
		return 'form_type_driver';
	}

}
