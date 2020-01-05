<?php
namespace Weskiller\Enum\Tests\Enums;

use Weskiller\Enum\UniqueValueEnum;

class UniqueEnum extends UniqueValueEnum
{

    /**
     * @descrption "this record is a post"
     * @extension '.post'
     */
    public const POST = 0x01;
    /**
     * @descrption "this record is a post comment"
     * @extension '.comment'
     */
    public const COMMENT = 0x02;
    /**
     * @descrption "this record is a comment replay"
     * @extension '.rep'
     */
    public const REPLAY = 0x03;

    /**
     * @descrption "this record is a video"
     * @extension '.v'
     */
    public const VIDEO = 0x14;
    /**
     * @descrption "this record is a audio"
     * @extension '.a'
     */
    public const AUDIO = 0x15;
    /**
     * @descrption "this record is a image"
     * @extension '.i'
     */
    public const IMAGE = 0x16;
    /**
     * @descrption "this record is a comment like"
     * @extension '.ag'
     */
    public const AGREE = 0x24;
    /**
     * @descrption "this record is a comment like"
     * @extension '.dag'
     */
    public const DISAGREE = 0x25;

    protected array $groups = [
        'text' => [
            self::POST,
            self::COMMENT,
            self::REPLAY,
        ],
        'media' => [
            self::VIDEO,
            self::AUDIO,
            self::IMAGE,
        ],
        'like' => [
            self::AGREE,
            self::DISAGREE,
        ]
    ];
}
