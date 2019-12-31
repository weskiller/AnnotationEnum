<?php

namespace Weskiller\Enum\Contract;

interface EnumInterface
{
    /**
     * Sure EnumClass Has A Constant
     * @param string $name
     * @return bool
     */
    public static function hasConstant(string $name) : bool;

    /**
     * Get EnumClass All Constants
     * @return array
     */
    public static function getConstants() : array;

    /**
     * Get EnumClass A Constant Value
     * @param string $name
     * @return mixed
     */
    public static function getConstant(string $name);

    /**
     * compare value
     * @param $element
     * @return bool
     */
    public function is($element) : bool;

    /**
     * Belong To $elements
     * @param $elements
     * @return bool
     */
    public function in($elements) : bool;
}