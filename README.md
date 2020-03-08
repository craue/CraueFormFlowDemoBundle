# Information

[![Build Status](https://travis-ci.org/craue/CraueFormFlowDemoBundle.svg?branch=3.2.x)](https://travis-ci.org/craue/CraueFormFlowDemoBundle)
[![Coverage Status](https://coveralls.io/repos/github/craue/CraueFormFlowDemoBundle/badge.svg?branch=3.2.x)](https://coveralls.io/github/craue/CraueFormFlowDemoBundle?branch=3.2.x)

CraueFormFlowDemoBundle contains the code used by http://craue.de/symfony-playground/en/CraueFormFlow/, a live demo
showcasing the features of [CraueFormFlowBundle](https://github.com/craue/CraueFormFlowBundle).

Take a branch of CraueFormFlowDemoBundle matching the version of CraueFormFlowBundle you're using.

To install in a Symfony 5 application:

    composer require craue/formflow-demo-bundle:@dev

Now edit your routes.yaml file and add the following:

```yaml
CraueFormFlowDemoBundle:
  resource: '@CraueFormFlowDemoBundle/Controller/'
  type: annotation
```

Now start your server and go to the demo page
    
    symfony server:start -d
    https://127.0.0.1:8000/CraueFormFlow/
    
