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
    private $serversLocator;

    public function __construct(HandlersLocatorInterface $serversLocator)
    {
        $this->serversLocator = $serversLocator;
    }

    public function handle(Request $request, string $serverKey): Response
    {
        return $this->serversLocator->getServer($serverKey)->handle($request);
    }
}