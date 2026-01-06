<?php

namespace Sergiobelya\DataStructures\SortedLinkedList;

/**
 * @internal
 */
class NodeFactory
{
    public function createNode(string|int $value): Node
    {
        return new Node($value);
    }
}
