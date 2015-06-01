<?php

namespace Craue\FormFlowDemoBundle\Form\ChoiceList;

use Craue\FormFlowDemoBundle\Entity\Vehicle;
use Symfony\Component\Form\Extension\Core\ChoiceList\LazyChoiceList;
use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2015 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class VehicleEngineChoiceList extends LazyChoiceList {

	/**
	 * @var TranslatorInterface
	 */
	protected $translator;

	public function setTranslator(TranslatorInterface $translator) {
		$this->translator = $translator;
	}

	protected function loadChoiceList() {
		$choices = array();

		foreach (Vehicle::getValidEngines() as $engine) {
			$choices[$engine] = $this->translator->trans($engine, array(), 'vehicleEngines');
		}

		asort($choices);

		return new SimpleChoiceList($choices);
	}

}
