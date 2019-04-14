# json-rpc-bundle
Symfony Json Rpc Bundle

## Requirements


        "php": "^7.2",
        "ext-json": "*",
        "symfony/framework-bundle": "^4.1",
        "symfony/messenger": "^4.2",
        "zendframework/zend-json-server": "^3.1"


## Installation

This package is installable and autoloadable via Composer 

```sh
composer require insidestyles/json-rpc-bundle
```

Update json rpc config.yaml

```yaml
json_rpc_api:
    handlers:
        main:
            path: /api
            host: ~
            serializer: ~
            logger: ~
```

Add Bundle to App
```php
    Insidestyles\JsonRpcBundle\JsonRpcBundle::class => ['all' => true],
```

Update routes.yaml

```yaml
_json_rpc_api:
  resource: .
  type: json_rpc_api

```

Config Symfony Messenger https://symfony.com/doc/current/messenger.html

## Usage

Add Example HelloWorldApi

```yaml
services:

    hello_world_api:
        class: Insidestyles\JsonRpcBundle\Api\HelloWorldApi
        arguments:
            - "@messenger.bus.default"
        tags:
            - {name: json_rpc_api, handler: main}

    hello_world_api_handler:
        class: Insidestyles\JsonRpcBundle\Handler\HelloWorldHandler
        tags:
            - {name: messenger.message_handler}

```

Add extra api endpoint with jms serializer and default symfony logger

```yaml
json_rpc_api:
    handlers:
        main:
            path: /api
            host: ~
            serializer: ~
            logger: ~
        extra:
            path: /extra_api
            host: ~
            serializer: json_rpc_api.serialzier.jms
            logger: monolog.logger
```

Add custom serializer adapter

```yaml
services:
    json_rpc_api.serialzier.jms:
        class: Insidestyles\JsonRpcBundle\Server\Adapter\Serializer\JmsSerializer
        arguments:
            - "@jms_serializer"
```


## Tips

- Install Symfony Validator and validation middle ware from messenger bus for message validation

```yaml
framework:
    messenger:
        buses:
            command_bus:
                middleware:
                    - messenger.middleware.validation
```

```php
<?php

namespace Insidestyles\JsonRpcBundle\Message;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class HelloWorldMessage
{
    /**
     * @Assert\NotBlank()
     * @var string
     */
    private $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}

``` 

- Go to api endpoint http:://localhost/api to see json-rpc methods 

```json
{
	"method": "Insidestyles\\JsonRpcBundle\\Sdk\\Contract\\JsonRpcApiInterface.helloWorld",
	"params": {
		"name": "test"
	}
}
```
