#!/bin/bash

set -euv

export COMPOSER_NO_INTERACTION=1

if [ "${TRAVIS_PHP_VERSION}" = '5.3.3' ]; then
	composer config -g disable-tls true
	composer config -g secure-http false
fi

composer self-update

case "${DEPS:-}" in
	'lowest')
		COMPOSER_UPDATE_ARGS='--prefer-lowest'
		;;
	'unmodified')
		# don't modify dependencies, install them as defined
		;;
	*)
		if [ -n "${MIN_STABILITY:-}" ]; then
			composer config minimum-stability "${MIN_STABILITY}"
		fi

		if [ -n "${SYMFONY_VERSION:-}" ]; then
			composer require --no-update --dev symfony/symfony:"${SYMFONY_VERSION}"
		fi
esac

composer update ${COMPOSER_UPDATE_ARGS:-}
