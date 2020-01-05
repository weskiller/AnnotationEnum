<?php


namespace Weskiller\Enum\Tests;

use PHPUnit\Framework\TestCase;
use Weskiller\Enum\Tests\Enums\DuplicationEnum as EnumClass;

class TestDuplicationEnumClass extends TestCase
{
    public function testNewClass(): void
    {
        $video = new EnumClass('DISAGREE');
        $this->assertEquals($video->value,EnumClass::DISAGREE);
        $this->assertEquals($video->key,'DISAGREE');
    }

    public function testInstance() :void
    {
        $post = EnumClass::instance('DISAGREE');
        $this->assertEquals($post->value,EnumClass::DISAGREE);
        $this->assertEquals($post->key,'DISAGREE');
    }

    public function testCallStatic() :void
    {
        $audio = EnumClass::AUDIO();
        $this->assertEquals($audio->value,EnumClass::AUDIO);
        $this->assertEquals($audio->key,'AUDIO');
    }

    public function testCoerce(): void
    {
        $comment1 = EnumClass::coerce(EnumClass::COMMENT);
        $this->assertInstanceOf(EnumClass::class,$comment1);
        $comment2 = EnumClass::coerce('COMMENT');
        $this->assertInstanceOf(EnumClass::class,$comment2);
        $this->assertEquals($comment1->key,$comment2->key);
        $this->assertEquals($comment1->value,$comment2->value);
    }

    public function testGetConstants()
    {
        $this->assertEquals([
            'POST' => EnumClass::POST,
            'COMMENT' => EnumClass::COMMENT,
            'REPLAY' => EnumClass::REPLAY,
            'VIDEO' => EnumClass::VIDEO,
            'AUDIO' => EnumClass::AUDIO,
            'IMAGE' => EnumClass::IMAGE,
            'AGREE' => EnumClass::AGREE,
            'DISAGREE' => EnumClass::DISAGREE,
        ],EnumClass::getConstants());
    }

    public function test()
    {

    }
}