# json-rpc-bundle
Symfony Json Rpc Bundle
Fast way to create json rpc microservice, using symfony messenger
and zend json server

- Json Rpc 2.0
- Batch Support
- Already maintained lib (symfony, Zend)

## Requirements


        "php": "^7.2",
        "ext-json": "*",
        "symfony/framework-bundle": "^4.1",
        "zendframework/zend-json-server": "^3.1"


## Installation

This package is installable and autoloadable via Composer 

```sh
composer require insidestyles/json-rpc-bundle
```

Add package config: json_rpc_api.yaml

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

Add route config: json_rpc_api_routing.yaml

```yaml
_json_rpc_api:
  resource: .
  type: json_rpc_api

```


## Usage

- Add Example HelloWorldApi

```yaml
services:

    hello_world_api:
        class: Insidestyles\JsonRpcBundle\Api\HelloWorldApi
        tags:
            - {name: json_rpc_api, handler: main}

```    

- Add Annotation To HelloWorldApi

Add requirement:

```sh
composer require doctrine/annotations
```

Enable annotation config: annotation => true

```yaml
json_rpc_api:
    handlers:
        main:
            path: /api
            host: ~
            serializer: ~
            logger: ~
            annotation: true
```              

Update api interface with JsonRpcApi Annotation

@JsonRpcApi(namespace = "main")

```php
<?php
    /**
     * @JsonRpcApi(namespace = "main")
     *
     * @author Fuong <insidestyles@gmail.com>
     */
    interface HelloWordJsonRpcApiInterface extends JsonRpcApiInterface
    {
        public function helloWorld(string $name);
    }
    
```

- HelloWorld Api with Symfony Messenger:

Add requirement:

```sh
composer require symfony/messenger
```

Config Symfony Messenger https://symfony.com/doc/current/messenger.html

```yaml
services:

    hello_world_api:
        class: Insidestyles\JsonRpcBundle\Api\MessageBus\HelloWorldApi
        arguments:
            - "@messenger.bus.default"
        tags:
            - {name: json_rpc_api, handler: main}
            - 
    hello_world_api_handler:
        class: Insidestyles\JsonRpcBundle\Handler\HelloWorldHandler
        tags:
            - {name: messenger.message_handler}
              
```

- Add extra api endpoint with jms serializer and default symfony logger

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

- Add custom serializer adapter

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

- You don't have to extend class AbstractApi if you don't want to use Symfony Messenger. 
Just implement your own interface that extends JsonRpcApiInterface. See example HelloWorldApi

- Go to api endpoint http:://localhost/api to see json-rpc methods 

- simple request content

```json
{
    "id": 1,
	"method": "Insidestyles\\JsonRpcBundle\\Sdk\\Contract\\JsonRpcApiInterface.helloWorld",
	"params": {
		"name": "test"
	}
}
```
- simple request content with annotation

```json
{
    "id": 1,
	"method": "main.helloWorld",
	"params": {
		"name": "test"
	}
}
```

- batch request content

```json
[
    {
        "id": 1,
        "method": "Insidestyles\\JsonRpcBundle\\Sdk\\Contract\\JsonRpcApiInterface.helloWorld",
        "params": {
            "name": "test"
        }
    },
    {
        "id": 2,
    	"method": "Insidestyles\\JsonRpcBundle\\Sdk\\Contract\\JsonRpcApiInterface.helloWorld",
    	"params": {
    		"name": "test"
    	}
    }
]
```

## Contribution

All are welcome