<?php

namespace Craue\FormFlowDemoBundle\Form\Type;

use Craue\FormFlowDemoBundle\Entity\Vehicle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2015 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class VehicleEngineType extends AbstractType {

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
		parent::configureOptions($resolver);

		$defaultOptions = array(
			'empty_value' => '',
		);

		$translator = $this->translator;
		$defaultOptions['choices'] = function(Options $options) use ($translator) {
			$choices = array();

			foreach (Vehicle::getValidEngines() as $engine) {
				$choices[$engine] = $translator->trans($engine, array(), 'vehicleEngines');
			}

			asort($choices);

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
		return 'choice';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return 'form_type_vehicleEngine';
	}

}
