<?php

declare(strict_types=1);

namespace LinkedList\tests\Message;

use LinkedList\Enum\OrderTypes;
use LinkedList\Exception\MixedValueTypeException;
use LinkedList\LinkedList;
use PHPUnit\Framework\TestCase;

class TypeTest extends TestCase
{
    public function testMixedType(): void
    {
        $list = new LinkedList(OrderTypes::ASC);
        $list->add(2);
        $this->expectException(MixedValueTypeException::class);
        $list->add('test');
    }
}
