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
    public function in(array $elements) : bool;

    /**
     * Convert To String
     * @return mixed
     */
    public function __toString();

    /**
     * Static Call Magic Function
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments);

    /**
     * Get Magic Function
     * @param $name
     * @return mixed
     */
    public function __get($name);

    /**
     * Set Magic Function
     * @param $name
     * @param $value
     * @return mixed
     */
    public function __set($name, $value);
}