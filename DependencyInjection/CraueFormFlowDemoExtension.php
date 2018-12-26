<?php

namespace Craue\FormFlowDemoBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Registration of the extension via DI.
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2013-2018 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CraueFormFlowDemoExtension extends Extension implements PrependExtensionInterface {

	/**
	 * {@inheritDoc}
	 */
	public function load(array $config, ContainerBuilder $container) {
		$loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
		$loader->load('controller.xml');
		$loader->load('form_flow.xml');
		$loader->load('form_type.xml');
		$loader->load('twig.xml');
	}

	/**
	 * {@inheritDoc}
	 */
	public function prepend(ContainerBuilder $container) {
		// avoid a deprecation notice regarding logout_on_user_change with Symfony 3.4
		// TODO remove as soon as Symfony >= 4.0 is required
		if (Kernel::MAJOR_VERSION === 3 && Kernel::MINOR_VERSION === 4) {
			$container->prependExtensionConfig('security', [
				'firewalls' => [
					'dummy' => [
						'logout_on_user_change' => true,
					],
				],
			]);
		}
	}

}
