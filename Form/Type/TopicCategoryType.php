<?php

namespace Craue\FormFlowDemoBundle\Form\Type;

use Craue\FormFlowDemoBundle\Entity\Topic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2018 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class TopicCategoryType extends AbstractType {

	/**
	 * @var TranslatorInterface
	 */
	protected $translator;

	public function setTranslator(TranslatorInterface $translator) {
		$this->translator = $translator;
	}

	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$useChoicesAsValues = method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix'); // Symfony's Form component >=2.8
		$usePlaceholder = method_exists('Symfony\Component\Form\AbstractType', 'configureOptions'); // Symfony's Form component 2.6 deprecated the "empty_value" option, but there seems to be no way to detect that version, so stick to this >=2.7 check.

		$defaultOptions = array(
			$usePlaceholder ? 'placeholder' : 'empty_value' => '',
		);

		if ($useChoicesAsValues) {
			$defaultOptions['choices_as_values'] = true;
		}

		$translator = $this->translator;
		$defaultOptions['choices'] = function(Options $options) use ($translator, $useChoicesAsValues) {
			$choices = array();

			foreach (Topic::getValidCategories() as $value) {
				$label = $translator->trans($value, array(), 'topicCategories');
				$choices[$useChoicesAsValues ? $label : $value] = $useChoicesAsValues ? $value : $label;
			}

			if ($useChoicesAsValues) {
				ksort($choices);
			} else {
				asort($choices);
			}

			return $choices;
		};

		$resolver->setDefaults($defaultOptions);
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
	public function getParent() {
		$useFqcn = method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix'); // Symfony's Form component >=2.8

		return $useFqcn ? 'Symfony\Component\Form\Extension\Core\Type\ChoiceType' : 'choice';
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
		return 'form_type_topicCategory';
	}

}
