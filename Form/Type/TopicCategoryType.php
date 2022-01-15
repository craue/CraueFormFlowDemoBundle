<?php

namespace Craue\FormFlowDemoBundle\Form\Type;

use Craue\FormFlowDemoBundle\Entity\Topic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface as LegacyTranslatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @internal
 */
abstract class BaseTopicCategoryType extends AbstractType {

	/**
	 * @var TranslatorInterface|LegacyTranslatorInterface
	 */
	protected $translator;

	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$defaultOptions = [
			'choice_translation_domain' => false,
			'placeholder' => '',
		];

		$defaultOptions['choices'] = function(Options $options) {
			$choices = [];

			foreach (Topic::getValidCategories() as $value) {
				$choices[$this->translator->trans($value, [], 'topicCategories')] = $value;
			}

			ksort($choices);

			return $choices;
		};

		$resolver->setDefaults($defaultOptions);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParent() : ?string {
		return ChoiceType::class;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getBlockPrefix() : string {
		return 'form_type_topicCategory';
	}

}

// TODO revert to one clean class definition as soon as Symfony >= 4.2 is required
if (!interface_exists(LegacyTranslatorInterface::class)) {
	/**
	 * @author Christian Raue <christian.raue@gmail.com>
	 * @copyright 2013-2022 Christian Raue
	 * @license http://opensource.org/licenses/mit-license.php MIT License
	 */
	class TopicCategoryType extends BaseTopicCategoryType {
		/**
		 * @required
		 */
		public function setTranslator(TranslatorInterface $translator) {
			$this->translator = $translator;
		}
	}
} else {
	/**
	 * @author Christian Raue <christian.raue@gmail.com>
	 * @copyright 2013-2022 Christian Raue
	 * @license http://opensource.org/licenses/mit-license.php MIT License
	 */
	class TopicCategoryType extends BaseTopicCategoryType {
		/**
		 * @required
		 */
		public function setTranslator(LegacyTranslatorInterface $translator) {
			$this->translator = $translator;
		}
	}
}
