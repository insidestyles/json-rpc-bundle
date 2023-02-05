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
    public function __construct(private readonly HandlersLocatorInterface $handlersLocator)
    {
    }

    public function __invoke(Request $request, string $serverKey): Response
    {
        return $this->handlersLocator->getHandler($serverKey)->handle($request);
    }
}
