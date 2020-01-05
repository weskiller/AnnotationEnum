<?php


namespace Weskiller\Enum;


use Weskiller\Enum\Contract\Enum;

/**
 * Class DuplicationValueEnum
 * @package Weskiller\Enum
 */
class DuplicationValueEnum extends Enum
{
    /**
     * @param $input
     * @return static|null
     * @throws Exception\InvalidEnumConstantException
     * @throws \ReflectionException
     */
    public static function coerce($input ): ?DuplicationValueEnum
    {
        if(static::valueOfConstantKey($input)) {
            return new static($input);
        }

        if($key = static::keyOfConstantValue($input)) {
            return new static($key);
        }
        return null;
    }
}