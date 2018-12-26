<?php

namespace Craue\FormFlowDemoBundle\Form;

use Craue\FormFlowDemoBundle\Entity\Topic;
use Craue\FormFlowDemoBundle\Form\Type\TopicCategoryType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2018 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CreateTopicForm extends AbstractType {

	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$isBugReport = $options['isBugReport'];

		switch ($options['flow_step']) {
			case 1:
				$builder->add('title');
				$builder->add('description', null, array(
					'required' => false,
				));
				$builder->add('category', TopicCategoryType::class);
				break;
			case 2:
				$builder->add('comment', TextareaType::class, array(
					'required' => false,
				));
				break;
			case 3:
				if ($isBugReport) {
					$builder->add('details', TextareaType::class);
				}
				break;
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data' => new Topic(),
			'isBugReport' => false,
		));
	}

	/**
	 * {@inheritDoc}
	 */
	public function getBlockPrefix() {
		return 'createTopic';
	}

}
