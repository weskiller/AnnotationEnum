<?php
namespace tests;

use PHPUnit\Framework\TestCase;
use Weskiller\Enum\Tests\Enums\UniqueEnum as EnumClass;

class TestUniqueEnumClass extends TestCase
{
    public function testNewClass(): void
    {
        $video = new EnumClass(EnumClass::VIDEO);
        $this->assertEquals($video->value,EnumClass::VIDEO);
        $this->assertEquals($video->key,'VIDEO');
    }

    public function testInstance() :void
    {
        $post = EnumClass::instance(EnumClass::POST);
        $this->assertEquals($post->value,EnumClass::POST);
        $this->assertEquals($post->key,'POST');
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

    public function test()
    {

    }
}