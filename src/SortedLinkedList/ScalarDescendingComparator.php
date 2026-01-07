<?php

namespace Sergiobelya\DataStructures\SortedLinkedList;

/**
 * @internal
 */
final class ScalarDescendingComparator implements ComparatorInterface
{
    public function isFirstNodeBeforeSecond(Node $firstNode, Node $secondNode): bool
    {
        return $firstNode->getValue() > $secondNode->getValue();
    }
}
