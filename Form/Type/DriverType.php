<?php

namespace Craue\FormFlowDemoBundle\Form\Type;

use Craue\FormFlowDemoBundle\Entity\Driver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2015 Christian Raue
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
			'data_class' => get_class(new Driver()),
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
		return $this->getBlockPrefix();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getBlockPrefix() {
		return 'form_type_driver';
	}

}
