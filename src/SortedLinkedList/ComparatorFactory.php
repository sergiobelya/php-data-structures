<?php

namespace Sergiobelya\DataStructures\SortedLinkedList;

/**
 * @internal
 */
final class ComparatorFactory
{
    public function create(int $sortOrder): ComparatorInterface
    {
        if ($sortOrder === SORT_ASC) {
            $comparator = new ScalarAscendingComparator();
        } else {
            $comparator = new ScalarDescendingComparator();
        }

        return $comparator;
    }
}
