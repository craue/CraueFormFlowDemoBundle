<?php

namespace Craue\FormFlowDemoBundle\Twig\Extension;

use Symfony\Component\Intl\Countries;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2022 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CountryExtension extends AbstractExtension {

	/**
	 * {@inheritDoc}
	 */
	public function getFunctions() : array {
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

		return Countries::exists($key) ? Countries::getName($key, \Locale::getDefault()) : null;
	}

}
