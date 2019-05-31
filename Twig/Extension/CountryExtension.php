<?php

namespace Craue\FormFlowDemoBundle\Twig\Extension;

use Symfony\Component\Intl\Countries;
use Symfony\Component\Intl\Intl;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2019 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CountryExtension extends AbstractExtension {

	/**
	 * {@inheritDoc}
	 */
	public function getFunctions() {
		return [
			new TwigFunction('country', [$this, 'getCountry']),
		];
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

		// TODO remove as soon as Symfony >= 4.3 is required
		if (!class_exists('Symfony\Component\Intl\Countries')) {
			return Intl::getRegionBundle()->getCountryName($key, \Locale::getDefault());
		}

		return Countries::exists($key) ? Countries::getName($key, \Locale::getDefault()) : null;
	}

}
