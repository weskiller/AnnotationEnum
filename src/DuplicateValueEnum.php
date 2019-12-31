<?php


namespace Weskiller\Enum;


use Weskiller\Enum\Contract\Enum;

class DuplicateValueEnum extends Enum
{
    protected array $elements = [];

    public function __construct($name,array $names = null)
    {
        parent::__construct($name);
        if($names) {
            $this->elements = static::validateConstantNames($names);
        }
    }

    public static function instance($name,...$elements)
    {
        return new static($name,static::transferArguments($elements)); // TODO: Change the autogenerated stub
    }

    public static function __callStatic($name, ...$arguments)
    {
        return static::instance($name,static::transferArguments($arguments));
    }

    public function is($element): bool
    {
        return $this->name == ($element instanceof static ? $element->name : $element);
    }

    public function in($names) :bool
    {
        return in_array($this->name, $names);
    }

    public function addElements(...$names)
    {
        $elements = static::transferArguments($names);
        $success = 0;
        foreach ($elements as $name) {
            if($value = self::nameOfConstantValue($name) and  !isset($this->elements[$name])) {
                $this->elements[$name] = $value;
                $success++;
            }
        }
        return $success;
    }

    public function removeElements(...$names)
    {
        $elements = static::transferArguments($names);
        $success = 0;
        foreach ($elements as $name) {
            if(isset($this->elements[$name])) {
                unset($this->elements[$name]);
                $success++;
            }
        }
        return $success;
    }

    public function isBelongTo()
    {
        return $this->in($this->elements);
    }
}