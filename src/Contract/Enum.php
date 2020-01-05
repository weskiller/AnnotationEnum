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
    /**
     * @var array
     */
    protected static array $enumReflection = [];

    /**
     * @var array
     */
    protected static array $constantAnnotations = [];

    /**
     * @var mixed|ClassConstants
     */
    protected ClassConstants $annotation;

    /**
     * @var string
     */
    public string $key;

    /**
     * @var mixed|string|null
     */
    public string $value;

    /**
     * @var string
     */
    protected string $annotationPrefix = '';

    /**
     * @var array
     */
    protected array $groups = [];

    /**
     * Enum constructor.
     * @param $key
     * @throws InvalidEnumConstantException
     * @throws \ReflectionException
     */
    public function __construct($key)
    {
        $this->value = static::validateConstantKey($key);
        $this->key = $key;
        $this->annotation = $this->getConstantAnnotation();
    }

    /**
     * @param $key
     * @return static
     * @throws InvalidEnumConstantException
     * @throws \ReflectionException
     */
    public static function instance($key)
    {
        return new static($key);
    }

    /**
     * @return mixed
     */
    protected function getConstantAnnotation()
    {
        if (!isset(static::$constantAnnotations[$this->key])) {
            static::$constantAnnotations[$this->key] = new ClassConstants(static::class, $this->key);
        }
        return static::$constantAnnotations[$this->key];
    }

    /**
     * @return mixed
     * @throws \ReflectionException
     */
    protected static function getReflectionClass()
    {
        if (!isset(self::$enumReflection[static::class])) {
            self::$enumReflection[static::class] = new \ReflectionClass(static::class);
        }
        return self::$enumReflection[static::class];
    }

    /**
     * @param string $key
     * @return mixed|null
     * @throws \ReflectionException
     */
    public static function getConstant(string $key)
    {
        return self::getConstants()[$key] ?? null;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public static function getConstants(): array
    {
        return self::getReflectionClass()->getConstants();
    }

    /**
     * @param string $key
     * @return bool
     * @throws \ReflectionException
     */
    public static function hasConstant(string $key): bool
    {
        return isset(self::getConstants()[$key]);
    }

    /**
     * @return array|mixed
     * @throws \ReflectionException
     */
    public static function getKeys()
    {
        return array_keys(static::getConstants());
    }

    /**
     * @param $key
     * @return mixed|null
     * @throws \ReflectionException
     */
    public static function valueOfConstantKey($key)
    {
        return static::getConstant($key);
    }

    /**
     * @param string $element
     * @return mixed|null
     * @throws InvalidEnumConstantException
     * @throws \ReflectionException
     */
    protected static function validateConstantKey(string $element)
    {
        if (!$value = static::hasConstant($element)) {
            throw new InvalidEnumConstantException(sprintf('invalid enum constants key %s::%s', static::class, $element));
        }
        return static::getConstant($element);
    }

    /**
     * @param array $elements
     * @return array
     * @throws InvalidEnumConstantException
     * @throws \ReflectionException
     */
    public static function validateConstantKeys(array $elements)
    {
        $result = [];
        foreach ($elements as $key) {
            $result[$key] = static::validateConstantKey($key);
        }
        return $result;
    }

    /**
     * @return array|mixed
     * @throws \ReflectionException
     */
    public static function getValues()
    {
        return array_values(static::getConstants());
    }

    /**
     * @param $value
     * @return false|int|string
     * @throws \ReflectionException
     */
    public static function keyOfConstantValue($value)
    {
        return array_search($value, static::getConstants(), true);
    }

    /**
     * @param $element
     * @return false|int|string
     * @throws InvalidEnumConstantException
     * @throws \ReflectionException
     */
    protected static function validateConstantValue($element)
    {
        if (!$name = static::keyOfConstantValue($element)) {
            throw new InvalidEnumConstantException(sprintf('invalid enum constants value %s::%s', static::class, $element));
        }
        return $name;
    }

    /**
     * @param array $elements
     * @return array
     * @throws \ReflectionException
     */
    public static function validateConstantValues(array $elements): array
    {
        $result = [];
        foreach ($elements as $value) {
            $result[$value] = static::keyOfConstantValue($value);
        }
        return $result;
    }

    /**
     * @param string $annotationPrefix
     * @throws InvalidEnumAnnotationPrefixException
     */
    public function setAnnotationPrefix(string $annotationPrefix): void
    {
        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_-]*$/i', $annotationPrefix)) {
            throw new InvalidEnumAnnotationPrefixException(sprintf('invalid enum constants annotation prefix (%s)', $annotationPrefix));
        }
        $this->annotationPrefix = $annotationPrefix;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function __get($key)
    {
        return $this->annotation->get($key, $this->annotationPrefix);
    }

    /**
     * @param $key
     * @param $value
     * @throws AssignToEnumException
     */
    public function __set($key, $value)
    {
        throw new AssignToEnumException(sprintf('assign %s to %s->%s', $value, static::class, $key));
    }

    /**
     * @param $name
     * @return bool
     * @throws \ReflectionException
     */
    public function __isset($name)
    {
        return static::hasConstant($name);
    }

    /**
     * @param $key
     * @param $arguments
     * @return static
     * @throws InvalidEnumConstantException
     * @throws \ReflectionException
     */
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
        return (array)count($values) > 1 ? $values : $values[0];
    }

    /**
     * @param $element
     * @return bool
     */
    public function is($element): bool
    {
        return $this->value === ($element instanceof static ? $element->value : $element);
    }

    /**
     * @param $values
     * @return bool
     */
    public function in($values): bool
    {
        foreach ($values as $value) {
            if ($this->is($value)) {
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
     * @throws InvalidEnumConstantException
     * @throws \ReflectionException
     */
    public function setGroups(array $groups): void
    {
        foreach ($groups as $group) {
            static::validateConstantKeys($group);
        }
        $this->groups = $groups;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getGroup(string $name)
    {
        return $this->hasGroup($name) ? $this->groups[$name] : null;
    }

    /**
     * @param string $name
     * @param array $value
     * @throws InvalidEnumConstantException
     * @throws \ReflectionException
     */
    public function addGroup(string $name, array $value): void
    {
        static::validateConstantKeys($value);
        $this->groups[$name] = $value;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function removeGroup(string $name): bool
    {
        if ($this->hasGroup($name)) {
            unset($this->groups[$name]);
            return true;
        }
        return false;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasGroup(string $name): bool
    {
        return isset($this->groups[$name]);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function isInGroup(string $name): bool
    {
        if ($this->hasGroup($name)) {
            return $this->in($this->getGroup($name));
        }
        return false;
    }
}
