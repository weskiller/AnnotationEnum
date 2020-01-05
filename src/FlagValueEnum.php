<?php

namespace Weskiller\Enum;

use Weskiller\Enum\Exception\InvalidEnumConstantException;

/**
 * Class FlagValueEnum
 * @package Weskiller\Enum
 */
class FlagValueEnum extends UniqueValueEnum
{
    /**
     * @param array $flags
     * @return Contract\Enum|null
     * @throws InvalidEnumConstantException
     * @throws \ReflectionException
     */
    public static function flags(array $flags = [])
    {
        self::validateConstantValues($flags);
        return static::instance(static::orFLags($flags));
    }

    /**
     * @param mixed ...$flags
     * @return int
     * @throws InvalidEnumConstantException
     */
    public function hasFlags(...$flags)
    {
        $flags = static::transferArguments($flags);
        static::validateFlags($flags);
        $value = static::orFLags($flags);
        return $value == $this->value & $value;
    }

    /**
     * @param mixed ...$flags
     * @throws InvalidEnumConstantException
     */
    public function addFlags(...$flags)
    {
        $flags = static::transferArguments($flags);
        static::validateFlags($flags);
        $this->value |= static::orFLags($flags);
    }

    /**
     * @param mixed ...$flags
     * @throws InvalidEnumConstantException
     */
    public function removeFlags(...$flags)
    {
        $flags = static::transferArguments($flags);
        static::validateFlags($flags);
        foreach ($flags as $flag) {
            $this->value &= ~ $flag;
        }
    }

    /**
     * @param mixed ...$flags
     * @return bool
     * @throws InvalidEnumConstantException
     */
    protected static function validateFlags(... $flags)
    {
        foreach ($flags as $flag) {
            foreach (static::getConstants() as $value) {
                $flag &= ~ (int) $value;
            }
            if($flag === 0) break;
            throw new InvalidEnumConstantException((sprintf('invalid enum constants flag value %s::%s', static::class, $flag)));
        }
        return true;
    }

    /**
     * @param array $flags
     * @return int|mixed
     */
    public static function orFLags(array $flags)
    {
        $result = 0;
        foreach ($flags as $flag) {
            $result |= $flag;
        }
        return $result;
    }

    /**
     * @return string
     */
    public function getBitString()
    {
        return decbin($this->value);
    }
}