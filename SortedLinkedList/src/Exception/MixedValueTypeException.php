<?php

declare(strict_types=1);

namespace LinkedList\Exception;

use Exception;

class MixedValueTypeException extends Exception
{
    public function __construct(string $type, string $allowedType)
    {
        parent::__construct("Not allowed type '{$type}' list already have type '{$allowedType}'");
    }
}
