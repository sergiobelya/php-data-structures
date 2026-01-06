<?php

namespace Sergiobelya\DataStructures\SortedLinkedList;

/**
 * @internal
 */
final class NodeFactory
{
    public function createNode(string|int $value): Node
    {
        return new Node($value);
    }
}
