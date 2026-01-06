<?php
declare(strict_types=1);

namespace Sergiobelya\DataStructures;

use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use Sergiobelya\DataStructures\SortedLinkedList\ComparatorFactory;
use Sergiobelya\DataStructures\SortedLinkedList\ComparatorInterface;
use Sergiobelya\DataStructures\SortedLinkedList\Node;
use Sergiobelya\DataStructures\SortedLinkedList\NodeFactory;
use Traversable;

class SortedLinkedList implements Countable, IteratorAggregate
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

    public function getIterator(): Traversable
    {
        $currentNode = $this->rootNode;
        while ($currentNode) {
            yield $currentNode->getValue();
            $currentNode = $currentNode->getNextNode();
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public function isValueExists(string|int $value): bool
    {
        $this->checkValueType($value);
        $tempNode = $this->nodeFactory->createNode($value);

        $exists = false;
        $currentNode = $this->rootNode;
        // iterate while $currentNode before or equal to checked value
        while ($currentNode && $this->isFirstNodeBeforeOrEqualToSecond($currentNode, $tempNode)) {
            if ($currentNode->getValue() === $value) {
                $exists = true;
                break;
            }
            $currentNode = $currentNode->getNextNode();
        }

        return $exists;
    }

    /**
     * Delete all nodes with values equal to $value
     * @throws InvalidArgumentException
     */
    public function delete(string|int $value): void
    {
        $this->checkValueType($value);
        $nodeBeforeValue = null;
        $currentNode = $this->rootNode;
        $tempNode = $this->nodeFactory->createNode($value);

        // iterate while $currentNode before or equal to checked value
        while ($currentNode && $this->isFirstNodeBeforeOrEqualToSecond($currentNode, $tempNode)) {
            if ($currentNode->getValue() === $value) {
                // delete $currentNode by linking next node to previous
                if ($nodeBeforeValue) {
                    $nodeAfterValue = $currentNode->getNextNode();
                    $nodeBeforeValue->setNextNode($nodeAfterValue);
                } else {
                    // if previous node is not exists, the $currentNode is root, so we'll change root
                    $this->rootNode = $currentNode->getNextNode();
                }
            } else {
                // $currentNode before checked value
                $nodeBeforeValue = $currentNode;
            }

            $currentNode = $currentNode->getNextNode();
        }
    }

    public function shift(): string|int|null
    {
        if ($this->rootNode === null) {
            return null;
        }

        $currentNode = $this->rootNode;
        $nextNode = $currentNode->getNextNode();
        $this->rootNode = $nextNode;

        return $currentNode->getValue();
    }

    public function pop(): string|int|null
    {
        if ($this->rootNode === null) {
            return null;
        }

        $previousNode = null;
        $lastNode = $this->rootNode;
        $currentNode = $this->rootNode;
        while ($currentNode->getNextNode()) {
            $currentNode = $currentNode->getNextNode();
            $previousNode = $lastNode;
            $lastNode = $currentNode;
        }

        if ($previousNode) {
            $previousNode->setNextNode(null);
        } else {
            $this->rootNode = null;
        }

        return $lastNode->getValue();
    }

    /**
     * @throws InvalidArgumentException
     */
    private function checkValueType(int|string $value): void
    {
        if (!$this->isEmpty()
            && ($this->isNotValidInt($value) || $this->isNotValidString($value))
        ) {
            throw new InvalidArgumentException("Value type should be the same as other values in SortedLinkedList");
        }
    }

    private function isFirstNodeBeforeOrEqualToSecond(Node $firstNode, Node $secondNode): bool
    {
        return !$this->comparator->isFirstNodeBeforeSecond($secondNode, $firstNode);
    }

    private function isFirstNodeBeforeSecond(Node $firstNode, Node $secondNode): bool
    {
        return $this->comparator->isFirstNodeBeforeSecond($firstNode, $secondNode);
    }
}
