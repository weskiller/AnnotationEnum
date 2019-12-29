<?php

namespace Weskiller\Enum\Contract;

use Weskiller\Enum\Annotation\ClassConstants;
use Weskiller\Enum\Exception\AssignToEnumException;
use Weskiller\Enum\Exception\InvalidEnumConstantException;

/**
 * Class Enum
 * @package Weskiller\Enum\Contract
 */
abstract class Enum implements EnumInterface
{
    protected static array $enumReflection = [];

    protected static array $constantAnnotations = [];

    protected ClassConstants $annotation;

    public string $name;

    public string $value;

    public function __construct($value)
    {
        $this->name = static::validateConstantValue($value);
        $this->value = static::getConstant($this->name);
        $this->annotation = $this->registerAnnotation();
    }

    public static function instance($value)
    {
        return new static($value);
    }

    public static function coerce($element)
    {
        if($value = static::valueOfConstantName($element)) {
            return new static($value);
        }
        if(static::nameOfConstantValue($element)) {
            return new static($element);
        }
        return null;
    }

    protected function registerAnnotation()
    {
        if(!isset(static::$constantAnnotations[$this->name])) {
            static::$constantAnnotations[$this->name] = new ClassConstants(static::class,$this->name);
        }
        return static::$constantAnnotations[$this->name];
    }

    private static function getReflectionClass()
    {
        $class = static::class;
        if(! isset(self::$enumReflection[$class])) {
            self::$enumReflection[$class] = new \ReflectionClass($class);
        }
        return self::$enumReflection[$class];
    }

    public static function getConstant(string $name)
    {
        return self::getConstants()[$name] ?? null;
    }

    public static function getConstants() : array
    {
        return self::getReflectionClass()->getConstants();
    }

    public static function hasConstant(string $name) :bool
    {
        return isset(self::getConstants()[$name]);
    }

    public static function nameOfConstantValue($value)
    {
        return array_search($value,static::getConstants());
    }

    public static function valueOfConstantName($name)
    {
        return static::getConstant($name);
    }

    protected static function validateConstantName(string $element)
    {
        if (!$value = static::hasConstant($element)) {
            throw new InvalidEnumConstantException(sprintf('invalid enum constants name %s::%s', static::class, $element));
        }
        return static::getConstant($element);
    }

    protected static function validateConstantValue($element)
    {
        if (!$name = static::nameOfConstantValue($element)) {
            throw new InvalidEnumConstantException(sprintf('invalid enum constants value %s::%s', static::class, $element));
        }
        return $name;
    }

    public function __toString()
    {
        return (string) $this->value;
    }

    public function is($element): bool
    {
        return $this->value == ($element instanceof static ? $element->value : $element);
    }

    public function in(array $elements) :bool
    {
        return in_array($this->value, $elements);
    }

    public static function __callStatic($name, $arguments)
    {
        return static::instance(self::validateConstantName($name));
    }

    public function __get($name)
    {
        return $this->annotation->get($name);
    }

    public function __set($name, $value)
    {
        throw new AssignToEnumException(sprintf('assign %s to %s->%s', $value, static::class, $name));
    }
}
