<?php

namespace Sergiobelya\DataStructures\SortedLinkedList;

/**
 * @internal
 */
interface ComparatorInterface
{
    public function isFirstNodeBeforeSecond(Node $firstNode, Node $secondNode): bool;
}
