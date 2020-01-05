<?php


namespace Weskiller\Enum;


use Weskiller\Enum\Contract\Enum;

/**
 * Class UniqueValueEnum
 * @package Weskiller\Enum
 */
class UniqueValueEnum extends Enum
{
    /**
     * UniqueValueEnum constructor.
     * @param $value
     * @throws Exception\InvalidEnumConstantException
     */
    public function __construct($value)
    {
        parent::__construct(static::validateConstantValue($value));
    }

    /**
     * @param array $groups
     */
    public function setGroups(array $groups): void
    {
        foreach ($groups as $group) {
            static::validateConstantValues($group);
        }
        $this->groups = $groups;
    }

    /**
     * @param string $name
     * @param array $value
     */
    public function addGroup(string $name, array $value) :void
    {
        self::validateConstantValues($value);
        $this->groups[$name] = $value;
    }
}