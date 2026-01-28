<?php

namespace LinkedList;

class Node
{
    public int|string $value;
    public ?Node $next = null;

    public function __construct(int|string $value)
    {
        $this->value = $value;
    }
}
