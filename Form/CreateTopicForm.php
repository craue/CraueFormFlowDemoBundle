<?php

namespace Craue\FormFlowDemoBundle\Form;

use Craue\FormFlowDemoBundle\Entity\Topic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2014 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CreateTopicForm extends AbstractType {

	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$isBugReport = $options['isBugReport'];

		switch ($options['flowStep']) {
			case 1:
				$builder->add('title');
				$builder->add('description', null, array(
					'required' => false,
				));
				$builder->add('category', 'form_type_topicCategory');
				break;
			case 2:
				$builder->add('comment', 'textarea', array(
					'required' => false,
				));
				break;
			case 3:
				if ($isBugReport) {
					$builder->add('details', 'textarea');
				}
				break;
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
			'flowStep' => 1,
			'data' => new Topic(),
			'isBugReport' => false,
		));
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return 'createTopic';
	}

}
