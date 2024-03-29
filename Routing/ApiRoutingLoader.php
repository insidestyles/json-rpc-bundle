<?php

namespace Insidestyles\JsonRpcBundle\Routing;

use Insidestyles\JsonRpcBundle\DependencyInjection\JsonRpcExtension;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Loads routing for servers
 *
 * @author Fuong <insidestyles@gmail.com>
 */
class ApiRoutingLoader extends Loader
{
    private array $paths = [];

    public function addPath($key, $host, $path, array $schemes = []): void
    {
        $this->paths[$key] = [
            'host' => $host,
            'path' => $path,
            'schemes' => $schemes,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function load($resource, $type = null): RouteCollection
    {
        $routes = new RouteCollection();
        $rPath = JsonRpcExtension::ALIAS . 'api_handle';

        foreach ($this->paths as $key => $pathInfo) {
            $defaults = [
                '_controller' => JsonRpcExtension::ALIAS . '.controller',
                'serverKey' => $key,
            ];

            $route = new Route(
                $pathInfo['path'],
                $defaults,
                [],
                [],
                $pathInfo['host'],
                $pathInfo['schemes']
            );

            $routes->add($rPath . $key, $route);
        }

        return $routes;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(mixed $resource, string $type = null): bool
    {
        return JsonRpcExtension::ALIAS == $type;
    }
}
