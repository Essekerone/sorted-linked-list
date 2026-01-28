<?php

declare(strict_types=1);

namespace LinkedList;

use Countable;
use IteratorAggregate;
use LinkedList\Enum\OrderTypes;
use LinkedList\Exception\MixedValueTypeException;
use LinkedList\Exception\NotAllowedTypeException;
use Traversable;

final class LinkedList implements Countable, IteratorAggregate
{
    private const array ALLOWED_TYPES = ['integer', 'string'];
    private ?string $type = null;
    private OrderTypes $order;
    private ?Node $head = null;
    private int $count = 0;

    public function __construct(OrderTypes $order)
    {
        $this->order = $order;
    }

    /**
     * @param int|string $value
     * @return void
     * @throws MixedValueTypeException
     * @throws NotAllowedTypeException
     */
    public function add(int|string $value): void
    {
        $this->assertType($value);

        $node = new Node($value);

        if ($this->head === null) {
            $this->count++;
            $this->head = $node;
            return;
        }

        if ($this->order === OrderTypes::ASC) {
            $this->addAsc($node);
        }

        if ($this->order === OrderTypes::DESC) { /// phpstan example
            $this->addDesc($node);
        }
    }

    /**
     * @param int|string $value
     * @return bool
     * @throws MixedValueTypeException
     * @throws NotAllowedTypeException
     */
    public function removeByValue(int|string $value): bool
    {
        if ($this->head === null) {
            return false;
        }

        $this->assertType($value);

        if ($this->verifyMoreOrLess($this->head->value, $value) === 0) {
            $this->head = $this->head->next;
            $this->count--;
            return true;
        }

        if ($this->order === OrderTypes::ASC) {
            return $this->removeAsc($value);
        }

        if ($this->order === OrderTypes::DESC) { /// phpstan example
            return $this->removeDesc($value);
        }
        return false;
    }

    /**
     * @param int|string $value
     * @return bool
     * @throws MixedValueTypeException
     * @throws NotAllowedTypeException
     */
    public function contains(int|string $value): bool
    {
        if ($this->head === null) {
            return false;
        }

        $this->assertType($value);

        if ($this->order === OrderTypes::ASC) {
            return $this->containsAsc($value);
        }

        if ($this->order === OrderTypes::DESC) {
            return $this->containsDesc($value);
        }
        return false;
    }

    private function containsDesc(int|string $value): bool
    {
        $current = $this->head;
        while ($current !== null) {
            $result = $this->verifyMoreOrLess($current->value, $value);
            if ($result === 0) {
                return true;
            }
            if ($result < 0) {
                return false;
            }
            $current = $current->next;
        }
        return false;
    }

    private function containsAsc(int|string $value): bool
    {
        $current = $this->head;
        while ($current !== null) {
            $result = $this->verifyMoreOrLess($current->value, $value);
            if ($result === 0) {
                return true;
            }
            if ($result > 0) {
                return false;
            }
            $current = $current->next;
        }
        return false;
    }

    public function first(): int|string
    {
        if ($this->head === null) {
            throw new \Exception('List is empty');
        }

        return $this->head->value;
    }

    public function last(): int|string
    {
        if ($this->head === null) {
            throw new \Exception('List is empty');
        }

        $current = $this->head;
        while ($current->next !== null) {
            $current = $current->next;
        }
        return $current->value;
    }

    public function toArray(): array
    {
        $result = [];
        $current = $this->head;

        while ($current !== null) {
            $result[] = $current->value;
            $current = $current->next;
        }

        return $result;
    }

    public function count(): int
    {
        return $this->count;
    }

    public function getIterator(): Traversable
    {
        $current = $this->head;

        while ($current !== null) {
            yield $current->value;
            $current = $current->next;
        }
    }

    /**
     * @param int|string $value
     * @return void
     * @throws NotAllowedTypeException
     * @throws MixedValueTypeException
     */
    private function assertType(int|string $value): void
    {
        $type = gettype($value);
        var_dump($type);

        if (!in_array($type, self::ALLOWED_TYPES)) {
            throw new NotAllowedTypeException();
        }

        if ($this->type === null) {
            $this->type = $type;
            return;
        }

        if ($this->type !== $type) {
            throw new MixedValueTypeException($type, $this->type);
        }
    }

    private function verifyMoreOrLess(int|string $a, int|string $b): int
    {
        return $a <=> $b;
    }

    private function addAsc(Node $node): void
    {
        if ($this->verifyMoreOrLess($node->value, $this->head->value) <= 0) {
            $node->next = $this->head;
            $this->head = $node;
            $this->count++;
            return;
        }


        $current = $this->head;
        while ($current->next !== null && $this->verifyMoreOrLess($node->value, $current->next->value) > 0) {
            $current = $current->next;
        }

        $node->next = $current->next;
        $current->next = $node;
        $this->count++;
    }

    private function addDesc(Node $node): void
    {
        if ($this->verifyMoreOrLess($node->value, $this->head->value) >= 0) {
            $node->next = $this->head;
            $this->head = $node;
            $this->count++;
            return;
        }


        $current = $this->head;
        while ($current->next !== null && $this->verifyMoreOrLess($node->value, $current->next->value) < 0) {
            $current = $current->next;
        }

        $node->next = $current->next;
        $current->next = $node;
        $this->count++;
    }

    private function removeAsc(int|string $value): bool
    {
        $current = $this->head;

        while ($current->next !== null && $this->verifyMoreOrLess($current->next->value, $value) < 0) {
            $current = $current->next;
        }

        if ($current->next === null) {
            return false;
        }

        $current->next = $current->next->next;
        $this->count--;
        return true;
    }

    private function removeDesc(int|string $value): bool
    {

        $current = $this->head;
        while ($current->next !== null && $this->verifyMoreOrLess($current->next->value, $value) > 0) {
            $current = $current->next;
        }

        if ($current->next === null) {
            return false;
        }

        $current->next = $current->next->next;
        $this->count--;
        return true;
    }
}
