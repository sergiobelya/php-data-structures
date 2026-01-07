# sergiobelya/data-structures
PHP library for data structures like Sorted Linked List

## Installation
```sh
composer require sergiobelya/data-structures
```

## Sorted Linked List
The data structure that consists of elements, each containing a link to the next element, and all elements are always kept sorted.
One instance of SortedLinkedList contains `int` or `string` values but not both.

### Available actions
- add
- delete
- shift
- pop
- isValueExists
- count
- reverse
- toArray
- iterate

### Usages
Notice: each `$value` used for one instance of `SortedLinkedList` should be `int` or `string` but not both, otherwise  `\InvalidArgumentException` will be thrown.

#### Add
```php
use Sergiobelya\DataStructures\SortedLinkedList;

// 1 example
$list = new SortedLinkedList();
while ($value = /* some function, e.g. fetch record from DB, read row from file, pop value from array, etc. */) {
    $list->add($value);
}

// 2 example
$sortedListInt = new SortedLinkedList();
$sortedListInt->add(5);
$sortedListInt->add(15);
$sortedListInt->add(10);

// 3 example
$sortedListString = new SortedLinkedList();
$sortedListString->add('USA');
$sortedListString->add('Canada');
$sortedListString->add('Mexico');
```

#### Delete
```php
// Delete value from SortedLinkedList<int>
$sortedListInt->delete(10);

// Delete value from SortedLinkedList<string>
$sortedListString->delete('Mexico');
```

#### Shift & Pop
```php
$firstValue = $list->shift();
$lastValue = $list->pop();
```

#### Is Value Exists
```php
if ($list->isValueExists('USA')) {
    // do something
}
```

#### Count
```php
$countItems = $list->count();
// or
$countItems = count($list);
```

#### Reverse
```php
// 1st option - call reverse() before add()
$sortedList = new SortedLinkedList();
$sortedList->reverse();
foreach ($originalData as $value) {
    // add values in descending order
    $sortedList->add($value);
}

// 2nd option - call reverse() after add()
$sortedList = new SortedLinkedList();
foreach ($originalData as $value) {
    // add values in ascending order
    $sortedList->add($value);
}
// reverse all values in descending order
$sortedList->reverse();
```

#### To Array
```php
$data = $list->toArray();
```

#### Iterations
```php
foreach ($list as $value) {
    // do something
}
```
