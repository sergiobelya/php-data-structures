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
        yield 'negative values' => [[-5, -10, 0, -8], [-10, -8, -5, 0]];
        yield 'full sort int' => [[0, 4, 5, 3, 15, 10, 5, -30, 25], [-30, 0, 3, 4, 5, 5, 10, 15, 25]];
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
        $this->assertSame(2, count($list));
    }

    public function testIteration(): void
    {
        $list = new SortedLinkedList();
        $list->add('a6');
        $list->add('a5');

        $concatResult = '';
        $i = 0;
        foreach ($list as $value) {
            $concatResult .= $value;
            $i++;
        }
        $this->assertSame('a5a6', $concatResult);
        $this->assertSame(2, $i);
    }

    public function testEmptyIteration(): void
    {
        $list = new SortedLinkedList();

        $concatResult = '';
        $i = 0;
        foreach ($list as $value) {
            $concatResult .= $value;
            $i++;
        }
        $this->assertSame('', $concatResult);
        $this->assertSame(0, $i);
    }

    public function testIsValueExistsInvalidType(): void
    {
        $list = new SortedLinkedList();
        $list->add('7');
        $list->add('5');

        $this->expectException(\InvalidArgumentException::class);
        $list->isValueExists(5);
    }

    public function testIsValueExistsString(): void
    {
        $list = new SortedLinkedList();
        $list->add('5');
        $list->add('7');

        $this->assertTrue($list->isValueExists('7'));
    }

    public function testIsValueExistsInt(): void
    {
        $list = new SortedLinkedList();
        $list->add(5);
        $list->add(7);

        $this->assertTrue($list->isValueExists(5));
    }

    public function testNotExists(): void
    {
        $list = new SortedLinkedList();
        $list->add(5);
        $list->add(7);

        $this->assertFalse($list->isValueExists(4));
        $this->assertFalse($list->isValueExists(6));
        $this->assertFalse($list->isValueExists(8));
    }

    public function testDelete()
    {
        $list = new SortedLinkedList();
        $list->add(5);
        $list->add(7);
        $list->add(5);
        $this->assertSame(3, $list->count());

        $list->delete(5);
        $this->assertSame(1, $list->count());

        $list->delete(4);
        $this->assertSame(1, $list->count());

        $list->delete(7);
        $this->assertSame(0, $list->count());

        $list->delete(18);
        $this->assertSame(0, $list->count());
    }

    public function testShift()
    {
        $list = new SortedLinkedList();
        $list->add(-7);
        $list->add(-5);

        $firstValue = $list->shift();
        $this->assertSame(-7, $firstValue);
        $this->assertSame(1, $list->count());

        $secondValue = $list->shift();
        $this->assertSame(-5, $secondValue);
        $this->assertSame(0, $list->count());

        $this->assertNull($list->shift());
    }

    public function testPop()
    {
        $list = new SortedLinkedList();
        $list->add('7');
        $list->add('5');

        $lastValue = $list->pop();
        $this->assertSame('7', $lastValue);
        $this->assertSame(1, $list->count());

        $lastValue = $list->pop();
        $this->assertSame('5', $lastValue);
        $this->assertSame(0, $list->count());

        $this->assertNull($list->pop());
    }
}
