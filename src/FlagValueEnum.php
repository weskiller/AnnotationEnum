<?php

namespace Weskiller\Enum;

use Weskiller\Enum\Exception\InvalidEnumConstantException;

class FlagValueEnum extends UniqueValueEnum
{
    public const NONE = 0;

    public static function flags(array $flags = [])
    {
        static::validateFlagValues($flags);
        static::instance(static::sumFlags($flags),$flags);
    }

    public function getFlags()
    {
        $flags = [];
        foreach (static::getConstants() as $constant) {
            if($this->hasFlags())
            $flags[] = $constant;
        }
        return $flags;
    }

    public function hasFlag(int $flag)
    {
        return ($this->value & $flag) == $flag;
    }

    public function hasFlags(...$flags)
    {
        foreach (static::transferArguments($flags) as $flag) {
            if(!$this->hasFlag($flag)) return false;
        }
        return true;
    }

    public function addFlags(...$flags)
    {
        $flags = static::transferArguments($flags);
        self::validateConstantValues($flags);
        foreach ($flags as $flag) {
            $this->value |= $flag;
        }
        return $this->value;
    }

    public function removeFlags(...$flags)
    {
        $flags = static::transferArguments($flags);
        self::validateConstantValues($flags);
        foreach ($flags as $flag) {
            $this->value ^= $flag;
        }
        return $this->value;
    }

    protected static function validateFlagValues(array $flags)
    {
        foreach ($flags as $flag) {
            foreach (static::getConstants() as $value) {
                $flag &= ~ $value;
            }
            if($flag === 0) break;
            throw new InvalidEnumConstantException((sprintf('invalid enum constants flag value %s::%s', static::class, $flag)));
        }
        return true;
    }

    public static function sumFlags(...$flags)
    {
        $result = 0;
        foreach (static::transferArguments($flags) as $flag) {
            return $result |= $flags;
        }
        return $result;
    }

    public function getBitString()
    {
        return decbin($this->value);
    }
}