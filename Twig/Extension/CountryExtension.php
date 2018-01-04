<?php

namespace Craue\FormFlowDemoBundle\Twig\Extension;

use Symfony\Component\Intl\Intl;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2018 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CountryExtension extends \Twig_Extension {

	/**
	 * {@inheritDoc}
	 */
	public function getFunctions() {
		return array(
			new \Twig_SimpleFunction('country', array($this, 'getCountry')),
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return 'country';
	}

	/**
	 * @param string|null $key
	 * @return string|null
	 */
	public function getCountry($key) {
		if (empty($key)) {
			return null;
		}

		$choices = Intl::getRegionBundle()->getCountryNames(\Locale::getDefault());

		if (array_key_exists($key, $choices)) {
			return $choices[$key];
		}
	}

}
