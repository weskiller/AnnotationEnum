<?php


namespace Weskiller\Enum\Tests\Enums;


use Weskiller\Enum\FlagValueEnum;

class FLagEnum extends FlagValueEnum
{
    /**
     * @name "read"
     */
    public const READABLE = 1 << 0;

    /**
     * @name "write"
     */
    public const WRITEABLE = 1 << 1;

    /**
     * @name "execute"
     */
    public const EXECUTABLE = 1 << 2;

    /**
     * @name "conceal"
     */
    public const CONCEALABLE = 1 << 3;

    /**
     * @name "delete"
     */
    public const DELETABLE = 1 << 4;
}