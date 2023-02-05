<?php
/**
 * User: insidestyles
 * Date: 22.04.19
 * Time: 12:18
 */

namespace Insidestyles\JsonRpcBundle\Annotation;

/**
 *
 * @Annotation
 * @Target("CLASS")
 *
 * @author Fuong <insidestyles@gmail.com>
 */
class JsonRpcApi
{
    final public const DEFAULT_NAMESPACE = 'json_rpc_api';

    public string $namespace;

    public function __construct(array $values)
    {
        $this->namespace = $values['namespace'] ?? self::DEFAULT_NAMESPACE;
    }
}
