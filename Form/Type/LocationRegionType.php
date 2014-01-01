<?php

namespace Craue\FormFlowDemoBundle\Form\Type;

use Craue\FormFlowDemoBundle\Model\Regions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2014 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class LocationRegionType extends AbstractType {

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
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		parent::setDefaultOptions($resolver);

		$defaultOptions = array(
			'country' => null,
			'empty_value' => '',
		);

		$translator = $this->translator;
		$defaultOptions['choices'] = function(Options $options) use ($translator) {
			$choices = array();

			foreach (Regions::getRegionsForCountry($options['country']) as $value) {
				$choices[$value] = $translator->trans($value, array(), 'locationRegions');
			}

			asort($choices);

			return $choices;
		};

		$resolver->setDefaults($defaultOptions);
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
		return 'form_type_locationRegion';
	}

}
