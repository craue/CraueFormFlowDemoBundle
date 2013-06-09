<?php

namespace Craue\FormFlowDemoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2013 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class TopicCategoryType extends AbstractType {

	/**
	 * @var ChoiceListInterface
	 */
	protected $choiceList;

	public function setChoiceList(ChoiceListInterface $choiceList) {
		$this->choiceList = $choiceList;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
			'choice_list' => $this->choiceList,
			'empty_value' => '',
		));
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParent() {
		return 'choice';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return 'form_type_topicCategory';
	}

}
