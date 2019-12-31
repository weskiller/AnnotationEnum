<?php

namespace Weskiller\Enum\Contract;

use Weskiller\Enum\Annotation\ClassConstants;
use Weskiller\Enum\Exception\AssignToEnumException;
use Weskiller\Enum\Exception\InvalidEnumAnnotationPrefixException;
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

    protected string $annotationPrefix = '';

    public function __construct($name)
    {
        static::validateConstantName($name);
        $this->name = $name;
        $this->value = static::getConstant($this->name);
        $this->annotation = $this->registerAnnotation();
    }

    public static function instance($name)
    {
        return new static($name);
    }

    public static function coerce($input,...$values)
    {
        if(static::valueOfConstantName($input)) {
            return new static($input, static::transferArguments($values));
        }
        else if($name = static::nameOfConstantValue($input)) {
            return new static($name,static::transferArguments($values));
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

    public static function validateConstantValues(array $elements)
    {
        $result = [];
        foreach ($elements as $value) {
            $result[$value] = static::nameOfConstantValue($value);
        }
        return $result;
    }

    public static function validateConstantNames(array $elements)
    {
        $result = [];
        foreach ($elements as $name) {
            $result[$name] = static::validateConstantName($name);
        }
        return $result;
    }

    public function setAnnotationPrefix(string $annotationPrefix): void
    {
        if(!preg_match('/^[a-zA-Z][a-zA-Z0-9_-]*$/i',$annotationPrefix)) {
            throw new InvalidEnumAnnotationPrefixException(sprintf('invalid enum constants annotation prefix (%s)',$annotationPrefix));
        }
        $this->annotationPrefix = $annotationPrefix;
    }

    public function __toString()
    {
        return (string) $this->value;
    }

    public function __get($name)
    {
        return $this->annotation->get($name,$this->annotationPrefix);
    }

    public function __set($name, $value)
    {
        throw new AssignToEnumException(sprintf('assign %s to %s->%s', $value, static::class, $name));
    }

    protected static function transferArguments(array $values)
    {
        return (array) count($values) > 1 ? $values : $values[0];
    }
}
