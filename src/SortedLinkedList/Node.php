<?php

namespace Sergiobelya\DataStructures\SortedLinkedList;

/**
 * @internal
 */
class Node
{
    private ?Node $nextNode = null;

    public function __construct(
        private readonly string|int $value
    ) {
    }

    public function getValue(): int|string
    {
        return $this->value;
    }

    public function getNextNode(): ?Node
    {
        return $this->nextNode;
    }

    public function setNextNode(?Node $nextNode): void
    {
        $this->nextNode = $nextNode;
    }
}
