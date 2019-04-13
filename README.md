# json-rpc-bundle
Symfony Json Rpc Bundle

## Requirements

* PHP >= 7.2
* symfony/framework-bundle: "^3.0|^4.0"
* symfony/console: "^3.0|^4.0"


## Installation

This package is installable and autoloadable via Composer 

```sh
composer require insidestyles/json-rpc-bundle
```
Update config.yml
```yaml
json_rpc_server:
```
Update AppKernel
```php
    new Insidestyles\JsonRpcBundle\JsonRpcBundle(),
```

## Usage
