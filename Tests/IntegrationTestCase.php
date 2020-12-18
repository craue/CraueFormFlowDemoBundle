<?php

namespace Craue\FormFlowDemoBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2020 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
abstract class IntegrationTestCase extends WebTestCase {

	/**
	 * @var Client|KernelBrowser|null
	 */
	protected static $client;

	/**
	 * {@inheritDoc}
	 */
	protected static function createKernel(array $options = []) {
		$configFile = isset($options['config']) ? $options['config'] : 'config.yml';

		return new AppKernel($configFile);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function setUp() : void {
		static::$client = static::createClient();
	}

	/**
	 * @param string $route
	 * @param array $parameters
	 * @return string URL
	 */
	protected function url($route, array $parameters = []) {
		return static::$kernel->getContainer()->get('router')->generate($route, $parameters);
	}

	/**
	 * @param string $id
	 * @param array $parameters
	 * @param string $domain
	 * @param string $locale
	 * @return string Text
	 */
	protected function trans($id, array $parameters = [], $domain = 'messages', $locale = 'en') {
		return static::$kernel->getContainer()->get('translator')->trans($id, $parameters, $domain, $locale);
	}

	/**
	 * @param Crawler $crawler
	 * @return string
	 */
	protected function getHtml(Crawler $crawler) {
		$html = '';

		foreach ($crawler as $domElement) {
			$html .= $domElement->ownerDocument->saveHTML();
		}

		return $html;
	}

	/**
	 * @param int|string $expectedStepNumber
	 * @param Crawler $crawler
	 */
	protected function assertCurrentStepNumber($expectedStepNumber, Crawler $crawler) {
		$this->assertStringStartsWith(sprintf('step %u: ', $expectedStepNumber), $this->getNodeText('fieldset legend', $crawler));
	}

	/**
	 * @param string $fieldSelector
	 * @param string $expectedError
	 * @param Crawler $crawler
	 */
	protected function assertFieldHasError($fieldSelector, $expectedError, Crawler $crawler) {
		$this->assertEquals($expectedError, $this->getNodeText('ul', $crawler->filter($fieldSelector)->siblings()));
	}

	/**
	 * @param string $imageSelector
	 * @param string $expectedSrcAttr
	 * @param Crawler $crawler
	 */
	protected function assertImageSrc($imageSelector, $expectedSrcAttr, Crawler $crawler) {
		$this->assertEquals($expectedSrcAttr, $this->getNodeAttribute($imageSelector, 'src', $crawler));
	}

	/**
	 * @param string $fieldSelector
	 * @param Crawler $crawler
	 * @return array
	 */
	protected function getOptionsOfSelectField($fieldSelector, Crawler $crawler) {
		return $crawler->filter('select' . $fieldSelector . ' option')->each(function(Crawler $node, $i) {
			return [
				'value' => $node->attr('value'),
				'label' => $node->text(),
			];
		});
	}

	/**
	 * @param string $listSelector
	 * @param Crawler $crawler
	 * @return array
	 */
	protected function getListContent($listSelector, Crawler $crawler) {
		$list = $crawler->filter('dl' . $listSelector);

		$dtValues = $list->filter('dt')->each(function(Crawler $node, $i) {
			return rtrim($node->text(), ':');
		});
		$ddValues = $list->filter('dd')->each(function(Crawler $node, $i) {
			return $node->text();
		});

		return array_combine($dtValues, $ddValues);

	}

	/**
	 * @param string $selector
	 * @param string $attribute
	 * @param Crawler $crawler
	 */
	private function getNodeAttribute($selector, $attribute, Crawler $crawler) {
		try {
			return $crawler->filter($selector)->attr($attribute);
		} catch (\InvalidArgumentException $e) {
			$this->fail(sprintf("No node found for selector '%s'. Content:\n%s", $selector, static::$client->getResponse()->getContent()));
		}
	}

	/**
	 * @param string $selector
	 * @param Crawler $crawler
	 */
	private function getNodeText($selector, Crawler $crawler) {
		try {
			return $crawler->filter($selector)->text();
		} catch (\InvalidArgumentException $e) {
			$this->fail(sprintf("No node found for selector '%s'. Content:\n%s", $selector, static::$client->getResponse()->getContent()));
		}
	}

}
