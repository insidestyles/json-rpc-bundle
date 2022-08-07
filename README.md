# json-rpc-bundle
Symfony Json Rpc Bundle
Fast way to create json rpc microservice, using symfony messenger
and laminas json server

- Json Rpc 2.0
- Batch Support

## Requirements


        "php": ">=8.0",
        "ext-json": "*",
        "symfony/framework-bundle": "^5.3" || ^6.0,
        "laminas/laminas-json-server": "^3.1"


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
            context: ~
            logger: ~
            error_handler: ~
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
            context: ~
            logger: ~
            error_handler: ~
        auth:
            path: /auth
            host: ~
            serializer: json_rpc_api.serialzier.jms
            context: ~
            logger: monolog.logger
            error_handler: ~
```

- Add custom serializer adapter

```sh
composer require jms/serializer-bundle
```

```yaml
services:
    json_rpc_api.serialzier.custom:
        class: Insidestyles\JsonRpcBundle\Server\Adapter\Serializer\CustomSerializer
        arguments:
            - "@serializer"
```

- Enable jms serializer

```yaml

json_rpc_api:
    handlers:
        main:
            path: /api
            host: ~
            serializer: json_rpc_api.serializer.jms
            context: json_rpc_api.serializer.default_context            
            logger: ~
            error_handler: ~

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
        "method": "main.helloWorld",
        "params": {
            "name": "hello1"
        }
    },
    {
        "id": 2,
    	"method": "main.helloWorld",
    	"params": {
    		"name": "hello2"
    	}
    }
]
```

- using remote service with other api endpoints

Add requirement:

```sh
composer ocramius/proxy-manager
```

Update service

```yaml

    hello.remote_services.hello:
        tags:
            -   name: 'json_rpc_remote_service'
                url: '%hello.api_server_url%'
                class: Insidestyles\JsonRpcBundle\Sdk\Contract\HelloWordJsonRpcApiInterface

```
Now you can call api
```php
    $container->get('hello.remote_services.hello')->helloWorld('Hi');
```
