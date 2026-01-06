<?php

namespace Sergiobelya\DataStructures\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sergiobelya\DataStructures\SortedLinkedList;

final class SortedLinkedListTest extends TestCase
{
    public function testIsEmpty(): void
    {
        $list = new SortedLinkedList();
        $this->assertTrue($list->isEmpty());
    }

    public function testAddNotEmpty(): void
    {
        $list = new SortedLinkedList();
        $list->add(5);
        $this->assertFalse($list->isEmpty());
    }

    #[DataProvider('dataAdd')]
    public function testAdd(array $inputData, array $expectedOutputData): void
    {
        $list = new SortedLinkedList();
        foreach ($inputData as $value) {
            $list->add($value);
        }
        $sortedData = $list->toArray();

        $this->assertEquals($expectedOutputData, $sortedData);
    }

    public static function dataAdd(): iterable
    {
        yield 'change root' => [[5, 4], [4, 5]];
        yield 'add last' => [[5, 6], [5, 6]];
        yield 'add between' => [[5, 15, 10], [5, 10, 15]];
        yield 'duplicated values' => [[4, 5, 5, 4], [4, 4, 5, 5]];
        yield 'full sort int' => [[4, 5, 3, 15, 10, 5, 25], [3, 4, 5, 5, 10, 15, 25]];
        yield 'full sort string' => [
            ['a4', 'a5', 'a3', 'b15', 'b10', 'a5', 'b25'],
            ['a3', 'a4', 'a5', 'a5', 'b10', 'b15', 'b25']
        ];
    }

    public function testAddChangeRoot()
    {
        $list = new SortedLinkedList();
        $list->add(5);
        $list->add(4);
        $arr = $list->toArray();

        $this->assertEquals([4, 5], $arr);
    }

    public function testAddLast()
    {
        $list = new SortedLinkedList();
        $list->add(5);
        $list->add(6);
        $arr = $list->toArray();

        $this->assertEquals([5, 6], $arr);
    }

    public function testAddBetween()
    {
        $list = new SortedLinkedList();
        $list->add(5);
        $list->add(15);
        $list->add(10);
        $arr = $list->toArray();

        $this->assertEquals([5, 10, 15], $arr);
    }

    public function testAddInvalidString(): void
    {
        $list = new SortedLinkedList();
        $list->add(5);
        $this->expectException(\InvalidArgumentException::class);
        $list->add('6');
    }

    public function testAddInvalidInt(): void
    {
        $list = new SortedLinkedList();
        $list->add('5');
        $this->expectException(\InvalidArgumentException::class);
        $list->add(6);
    }

    public function testCount(): void
    {
        $list = new SortedLinkedList();
        $this->assertSame(0, $list->count());
        $list->add('5');
        $this->assertSame(1, $list->count());
        $list->add('6');
        $this->assertSame(2, $list->count());
    }
}
