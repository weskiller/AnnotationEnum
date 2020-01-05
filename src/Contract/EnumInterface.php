<?php

namespace Weskiller\Enum\Contract;

interface EnumInterface
{
    /**
     * Sure EnumClass Has A Constant
     * @param string $key
     * @return bool
     */
    public static function hasConstant(string $key) : bool;

    /**
     * Get EnumClass All Constants
     * @return array
     */
    public static function getConstants() : array;

    /**
     * Get EnumClass A Constant Value
     * @param string $key
     * @return mixed
     */
    public static function getConstant(string $key);

    /**
     * Get EnumClass all key
     * @return mixed
     */
    public static function getKeys();

    /**
     * Get EnumClass all value
     * @return mixed
     */
    public static function getValues();

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