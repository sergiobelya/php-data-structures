<?php
declare(strict_types=1);

namespace Sergiobelya\DataStructures;

use Countable;
use InvalidArgumentException;
use Sergiobelya\DataStructures\SortedLinkedList\ComparatorFactory;
use Sergiobelya\DataStructures\SortedLinkedList\ComparatorInterface;
use Sergiobelya\DataStructures\SortedLinkedList\Node;
use Sergiobelya\DataStructures\SortedLinkedList\NodeFactory;

class SortedLinkedList implements Countable
{
    private ?Node $rootNode;

    private NodeFactory $nodeFactory;

    private ComparatorFactory $comparatorFactory;

    /**
     * @var int one of php constants: SORT_ASC, SORT_DESC
     */
    private int $sortOrder;

    private ComparatorInterface $comparator;

    public function __construct()
    {
        $this->rootNode = null;
        $this->sortOrder = SORT_ASC;
        $this->nodeFactory = new NodeFactory();
        $this->comparatorFactory = new ComparatorFactory();
        $this->comparator = $this->comparatorFactory->create($this->sortOrder);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function add(string|int $value): void
    {
        if ($this->rootNode === null) {
            $this->rootNode = $this->nodeFactory->createNode($value);
        } else {
            $this->checkValueType($value);
            $addedNode = $this->nodeFactory->createNode($value);
            if ($this->isNodeBeforeRoot($addedNode)) {
                $this->changeRoot($addedNode);
            } else {
                $this->insertBetweenNodes($addedNode);
            }
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    private function checkValueType(int|string $value): void
    {
        if ($this->isNotValidInt($value)
            || $this->isNotValidString($value)) {
            throw new InvalidArgumentException("Value type should be the same as other values in SortedLinkedList");
        }
    }

    private function isNotValidInt(int|string $value): bool
    {
        return is_int($value) && !is_int($this->rootNode->getValue());
    }

    private function isNotValidString(int|string $value): bool
    {
        return is_string($value) && !is_string($this->rootNode->getValue());
    }

    private function isNodeBeforeRoot(Node $node): bool
    {
        return $this->comparator->isFirstNodeBeforeSecond($node, $this->rootNode);
    }

    private function changeRoot(Node $addedNode): void
    {
        $addedNode->setNextNode($this->rootNode);
        $this->rootNode = $addedNode;
    }

    private function insertBetweenNodes(Node $addedNode): void
    {
        $currentNode = $this->rootNode;
        while ($currentNode->getNextNode() && $this->isFirstNodeBeforeSecond($currentNode->getNextNode(), $addedNode)) {
            $currentNode = $currentNode->getNextNode();
        }
        // insert added node between current and next nodes (if next node is null then added node will be last)
        $nextNode = $currentNode->getNextNode();
        $currentNode->setNextNode($addedNode);
        $addedNode->setNextNode($nextNode);
    }

    private function isFirstNodeBeforeSecond(Node $firstNode, Node $secondNode): bool
    {
        return $this->comparator->isFirstNodeBeforeSecond($firstNode, $secondNode);
    }

    public function isEmpty(): bool
    {
        return $this->rootNode === null;
    }

    public function count(): int
    {
        $count = 0;
        $currentNode = $this->rootNode;
        while ($currentNode) {
            $count++;
            $currentNode = $currentNode->getNextNode();
        }

        return $count;
    }

    public function toArray(): array
    {
        $result = [];
        $currentNode = $this->rootNode;
        while ($currentNode) {
            $result[] = $currentNode->getValue();
            $currentNode = $currentNode->getNextNode();
        }

        return $result;
    }
}
