<?php
/**
 * User: insidestyles
 * Date: 12.04.19
 * Time: 10:43
 */

namespace Insidestyles\JsonRpcBundle\Server\Handler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
interface JsonRpcHandlerInterface
{
    /**
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request): Response;
}
