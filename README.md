# Information

[![Build Status](https://travis-ci.org/craue/CraueFormFlowDemoBundle.svg?branch=3.3.x)](https://travis-ci.org/craue/CraueFormFlowDemoBundle)
[![Coverage Status](https://coveralls.io/repos/github/craue/CraueFormFlowDemoBundle/badge.svg?branch=3.3.x)](https://coveralls.io/github/craue/CraueFormFlowDemoBundle?branch=3.3.x)

CraueFormFlowDemoBundle contains the code used by http://craue.de/symfony-playground/en/CraueFormFlow/, a live demo
showcasing the features of [CraueFormFlowBundle](https://github.com/craue/CraueFormFlowBundle).

Take a branch of CraueFormFlowDemoBundle matching the version of CraueFormFlowBundle you're using.

# Installation

## Get the bundle

Let Composer download and install the bundle by running

```sh
composer require craue/formflow-demo-bundle:@dev
```

in a shell.

## Enable the bundle

If you don't use Symfony Flex, register it manually:

```php
// in config/bundles.php
return [
	// ...
	Craue\FormFlowDemoBundle\CraueFormFlowBundle::class => ['all' => true],
	Craue\FormFlowDemoBundle\CraueFormFlowDemoBundle::class => ['all' => true],
];
```

Or, for Symfony 3.4:

```php
// in app/AppKernel.php
public function registerBundles() {
	$bundles = [
		// ...
		new Craue\FormFlowBundle\CraueFormFlowBundle(),
		new Craue\FormFlowBundle\CraueFormFlowDemoBundle(),
	];
	// ...
}
```

## Add the routes

```yaml
# in config/routes.yaml
CraueFormFlowDemoBundle:
  resource: '@CraueFormFlowDemoBundle/Controller/'
  type: annotation
```

# Usage

Start your server:

```sh
symfony serve
```

Go to the demo page:

```
https://127.0.0.1:8000/CraueFormFlow/
```
