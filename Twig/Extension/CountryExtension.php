<?php

namespace Craue\FormFlowDemoBundle\Twig\Extension;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Intl\Intl;
use Symfony\Component\Locale\Locale;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2016 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CountryExtension extends \Twig_Extension {

	/**
	 * {@inheritDoc}
	 */
	public function getFunctions() {
		if (version_compare(\Twig_Environment::VERSION, '1.12', '<')) {
			return array(
				'country' => new \Twig_Function_Method($this, 'getCountry'),
			);
		}

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

		$locale = \Locale::getDefault();

		if (Kernel::VERSION_ID < 20300) {
			$choices = Locale::getDisplayCountries($locale);
		} else {
			$choices = Intl::getRegionBundle()->getCountryNames($locale);
		}

		if (array_key_exists($key, $choices)) {
			return $choices[$key];
		}
	}

}
