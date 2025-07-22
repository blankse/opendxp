# Testing Symfony Services

For integration tests of symfony services in context of their configuration in container, there are multiple ways for 
retrieving them directly from the symfony container (regardless if there are public or private). 

The symfony default way is described [here](https://symfony.com/doc/current/testing.html#retrieving-services-in-the-test)
and can be used in context within OpenDXP too. 

In combination with codeception, where is the [codeception symfony module](https://codeception.com/docs/modules/Symfony) 
that provides additional functionality as also grabbing services from 
[the container](https://codeception.com/docs/modules/Symfony#grabService). 

Currently, we are not using the [codeception symfony module](https://codeception.com/docs/modules/Symfony) though, 
to reduce test complexity and due to lack of compatibility with symfony 6.

To still have the grab service functionality available, just use the 
[`OpenDxp\Tests\Support\Helper\OpenDxp`](https://github.com/open-dxp/opendxp/blob/1.x/tests/Support/Helper/OpenDxp.php#L101) 
module and call `grabService` as below: 

```php
use OpenDxp\Tests\Support\Helper\OpenDxp;

/** @var OpenDxp $openDxpModule */
$openDxpModule = $this->getModule('\\' . OpenDxp::class);
$mailerService = $openDxpModule->grabService('mailer');
```
