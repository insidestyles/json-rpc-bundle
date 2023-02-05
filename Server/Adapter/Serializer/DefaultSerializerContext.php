<?php
/**
 * User: insidestyles
 * Date: 14.04.19
 */

namespace Insidestyles\JsonRpcBundle\Server\Adapter\Serializer;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class DefaultSerializerContext implements SerializerContextInterface
{
    private array $groups = [];

    public function getGroups(): array
    {
        return $this->groups;
    }

    public function setGroups(array $groups)
    {
        $this->groups = $groups;
    }
}
