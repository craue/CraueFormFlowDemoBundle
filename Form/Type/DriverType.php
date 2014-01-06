<?php

namespace Craue\FormFlowDemoBundle\Form\Type;

use Craue\FormFlowDemoBundle\Entity\Driver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2014 Christian Raue
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
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
			'data_class' => get_class(new Driver()),
		));
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return 'form_type_driver';
	}

}
