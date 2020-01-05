<?php

namespace Weskiller\Enum;

use Weskiller\Enum\Exception\InvalidEnumConstantException;

class FlagValueEnum extends UniqueValueEnum
{
    public static function flags(array $flags = [])
    {
        self::validateConstantValues($flags);
        return static::instance(static::orFLags($flags));
    }

    public function hasFlags(...$flags)
    {
        $flags = static::transferArguments($flags);
        static::validateFlags($flags);
        $value = static::orFLags($flags);
        return $value == $this->value & $value;
    }

    public function addFlags(...$flags)
    {
        $flags = static::transferArguments($flags);
        static::validateFlags($flags);
        $this->value |= static::orFLags($flags);
    }

    public function removeFlags(...$flags)
    {
        $flags = static::transferArguments($flags);
        static::validateFlags($flags);
        foreach ($flags as $flag) {
            $this->value &= ~ $flag;
        }
    }

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

    public static function orFLags(array $flags)
    {
        $result = 0;
        foreach ($flags as $flag) {
            $result |= $flag;
        }
        return $result;
    }

    public function getBitString()
    {
        return decbin($this->value);
    }
}