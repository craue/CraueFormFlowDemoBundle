<?php

namespace Craue\FormFlowDemoBundle\Twig\Extension;

use Symfony\Component\Locale\Locale;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2015 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CountryExtension extends \Twig_Extension {

	/**
	 * {@inheritDoc}
	 */
	public function getFunctions() {
		return array(
			'country' => new \Twig_Function_Method($this, 'getCountry'),
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
		if (!empty($key)) {
			$choices = Locale::getDisplayCountries(\Locale::getDefault());

			if (array_key_exists($key, $choices)) {
				return $choices[$key];
			}
		}
	}

}
