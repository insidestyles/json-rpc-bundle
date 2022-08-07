<?php

namespace Insidestyles\JsonRpcBundle\Controller;

use Insidestyles\JsonRpcBundle\Server\HandlersLocatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
final class ApiController extends AbstractController
{
    private HandlersLocatorInterface $handlersLocator;

    public function __construct(HandlersLocatorInterface $handlersLocator)
    {
        $this->handlersLocator = $handlersLocator;
    }

    public function handle(Request $request, string $serverKey): Response
    {
        return $this->handlersLocator->getHandler($serverKey)->handle($request);
    }
}