<?php

declare(strict_types=1);

require __DIR__ . '/SortedLinkedList/src/LinkedList.php';
require __DIR__ . '/SortedLinkedList/src/Enum/OrderTypes.php';
require __DIR__ . '/SortedLinkedList/src/Exception/NotAllowedTypeException.php';
require __DIR__ . '/SortedLinkedList/src/Exception/MixedValueTypeException.php';
require __DIR__ . '/SortedLinkedList/src/Node.php';

use LinkedList\LinkedList;
use LinkedList\Enum\OrderTypes;

$list = new LinkedList(OrderTypes::ASC);

$list->add(10);
$list->add(2);
$list->add(7);
$list->add(1);

var_dump($list->toArray());

foreach ($list as $value) {
    echo $value . "\n";
}

var_dump($list->contains(7));
echo PHP_EOL;

$list->removeByValue(23);

var_dump($list->toArray());

$list = new LinkedList(OrderTypes::DESC);

$list->add("A");
$list->add("B");
$list->add("C");
var_dump($list->first());
var_dump($list->last());

var_dump($list->toArray());