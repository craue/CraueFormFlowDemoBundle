<?php

namespace Craue\FormFlowDemoBundle\Form\Type;

use Craue\FormFlowDemoBundle\Model\Regions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface as LegacyTranslatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @internal
 */
abstract class BaseLocationRegionType extends AbstractType {

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
			'country' => null,
			'placeholder' => '',
		];

		$defaultOptions['choices'] = function(Options $options) {
			$choices = [];

			foreach (Regions::getRegionsForCountry($options['country']) as $value) {
				$choices[$this->translator->trans($value, [], 'locationRegions')] = $value;
			}

			ksort($choices);

			return $choices;
		};

		$resolver->setDefaults($defaultOptions);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParent() {
		return ChoiceType::class;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getBlockPrefix() {
		return 'form_type_locationRegion';
	}

}

// TODO revert to one clean class definition as soon as Symfony >= 4.2 is required
if (interface_exists(TranslatorInterface::class)) {
	/**
	 * @author Christian Raue <christian.raue@gmail.com>
	 * @copyright 2013-2019 Christian Raue
	 * @license http://opensource.org/licenses/mit-license.php MIT License
	 */
	class LocationRegionType extends BaseLocationRegionType {
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
	 * @copyright 2013-2019 Christian Raue
	 * @license http://opensource.org/licenses/mit-license.php MIT License
	 */
	class LocationRegionType extends BaseLocationRegionType {
		/**
		 * @required
		 */
		public function setTranslator(LegacyTranslatorInterface $translator) {
			$this->translator = $translator;
		}
	}
}
