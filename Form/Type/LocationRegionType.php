<?php

namespace Craue\FormFlowDemoBundle\Form\Type;

use Craue\FormFlowDemoBundle\Model\Regions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2019 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class LocationRegionType extends AbstractType {

	/**
	 * @var TranslatorInterface
	 */
	protected $translator;

	/**
	 * @required
	 */
	public function setTranslator(TranslatorInterface $translator) {
		$this->translator = $translator;
	}

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
