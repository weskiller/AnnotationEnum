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

    public string $key;

    public string $value;

    protected string $annotationPrefix = '';

    public array $groups = [];

    public function __construct($key)
    {
        $this->value = static::validateConstantKey($key);
        $this->key = $key;
        $this->annotation = $this->getConstantAnnotation();
    }

    public static function instance($key)
    {
        return new static($key);
    }

    public static function coerce($input)
    {
        if(static::valueOfConstantKey($input)) {
            return new static($input);
        }
        else if($key = static::keyOfConstantValue($input)) {
            return new static($key);
        }
        return null;
    }

    protected function getConstantAnnotation()
    {
        if(!isset(static::$constantAnnotations[$this->key])) {
            static::$constantAnnotations[$this->key] = new ClassConstants(static::class,$this->key);
        }
        return static::$constantAnnotations[$this->key];
    }

    protected static function getReflectionClass()
    {
        if(! isset(self::$enumReflection[static::class])) {
            self::$enumReflection[static::class] = new \ReflectionClass(static::class);
        }
        return self::$enumReflection[static::class];
    }

    public static function getConstant(string $key)
    {
        return self::getConstants()[$key] ?? null;
    }

    public static function getConstants() : array
    {
        return self::getReflectionClass()->getConstants();
    }

    public static function hasConstant(string $key) :bool
    {
        return isset(self::getConstants()[$key]);
    }

    public static function getKeys()
    {
        return array_keys(static::getConstants());
    }

    public static function valueOfConstantKey($key)
    {
        return static::getConstant($key);
    }

    protected static function validateConstantKey(string $element)
    {
        if (!$value = static::hasConstant($element)) {
            throw new InvalidEnumConstantException(sprintf('invalid enum constants key %s::%s', static::class, $element));
        }
        return static::getConstant($element);
    }

    public static function validateConstantKeys(array $elements)
    {
        $result = [];
        foreach ($elements as $key) {
            $result[$key] = static::validateConstantKey($key);
        }
        return $result;
    }

    public static function getValues()
    {
        return array_values(static::getConstants());
    }

    public static function keyOfConstantValue($value)
    {
        return array_search($value,static::getConstants());
    }

    protected static function validateConstantValue($element)
    {
        if (!$name = static::keyOfConstantValue($element)) {
            throw new InvalidEnumConstantException(sprintf('invalid enum constants value %s::%s', static::class, $element));
        }
        return $name;
    }

    public static function validateConstantValues(array $elements)
    {
        $result = [];
        foreach ($elements as $value) {
            $result[$value] = static::keyOfConstantValue($value);
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

    public function __get($key)
    {
        return $this->annotation->get($key,$this->annotationPrefix);
    }

    public function __set($key, $value)
    {
        throw new AssignToEnumException(sprintf('assign %s to %s->%s', $value, static::class, $key));
    }

    public static function __callStatic($key, $arguments)
    {
        return static::instance($key);
    }

    /**
     * @param array $values
     * @return array|mixed
     */
    protected static function transferArguments(array $values)
    {
        return (array) count($values) > 1 ? $values : $values[0];
    }

    public function is($element): bool
    {
        return $this->value == ($element instanceof static ? $element->value : $element);
    }

    public function in($values): bool
    {
        foreach ($values as $value) {
            if($this->is($value)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return array
     */
    public function getGroups(): array
    {
        return $this->groups;
    }
    /**
     * @param array $groups
     */
    public function setGroups(array $groups): void
    {
        foreach ($groups as $group) {
            static::validateConstantKeys($group);
        }
        $this->groups = $groups;
    }

    public function getGroup(string $name)
    {
        return $this->hasGroup($name) ? $this->groups[$name] : null;
    }

    public function addGroup(string $name,array $value) :void
    {
        static::validateConstantKeys($value);
        $this->groups[$name] = $value;
    }

    public function removeGroup(string $name) :bool
    {
        if($this->hasGroup($name)) {
            unset($this->groups[$name]);
            return true;
        }
        return false;
    }

    public function hasGroup(string $name) : bool
    {
        return isset($this->groups[$name]);
    }

    public function isInGroup(string $name) :bool
    {
        if($this->hasGroup($name)) {
            return $this->in($this->getGroup($name));
        }
        return false;
    }
}
