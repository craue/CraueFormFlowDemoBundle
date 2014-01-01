<?php

namespace Craue\FormFlowDemoBundle\Form\ChoiceList;

use Craue\FormFlowDemoBundle\Entity\Topic;
use Symfony\Component\Form\Extension\Core\ChoiceList\LazyChoiceList;
use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2014 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class TopicCategoryChoiceList extends LazyChoiceList {

	/**
	 * @var TranslatorInterface
	 */
	protected $translator;

	public function setTranslator(TranslatorInterface $translator) {
		$this->translator = $translator;
	}

	protected function loadChoiceList() {
		$choices = array();

		foreach (Topic::getValidCategories() as $possibleChoice) {
			$choices[$possibleChoice] = $this->translator->trans($possibleChoice, array(), 'topicCategories');
		}

		asort($choices);

		return new SimpleChoiceList($choices);
	}

}
