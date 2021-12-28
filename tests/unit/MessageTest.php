<?php

namespace Sensorario\Yaphlo;

class MessageTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    public function isAnEmptyArrayIfNoItemWerePassedToConstructor()
    {
        $message = new Message();
        $this->assertEquals([], $message->content());
    }

    /** @test */
    public function contentIsTheSameSentViaConstructor()
    {
        $content = [
            'foo' => 'bar'
        ];
        $message = new Message();
        $message->setContent($content);
        $this->assertEquals($content, $message->content());
    }

    /** @test */
    public function renderEachLineWithDateAndTime()
    {
        $content = [
            'foo' => 'bar'
        ];

        $currentDateTime = new \DateTime();

        $datetime = $currentDateTime->format('[Y-m-d H:i:s]');

        $renderedContent = <<<JSON
        $datetime {
        $datetime     "foo": "bar"
        $datetime }
        JSON;

        $message = new Message();
        $message->setContent($content);
        $this->assertEquals($renderedContent, $message->render());
    }

    /** @test */
    public function displayLevelOnlyWhenRequested()
    {
        $content = [
            'foo' => 'bar'
        ];

        $currentDateTime = new \DateTime();

        $datetime = $currentDateTime->format('[Y-m-d H:i:s]');

        $renderedContent = <<<JSON
        $datetime [INFO] {
        $datetime [INFO]     "foo": "bar"
        $datetime [INFO] }
        JSON;

        $message = new Message();
        $message->setContent($content);
        $message->setLevel(Message::LEVEL_INFO);

        $this->assertEquals($renderedContent, $message->render());
    }

    /** @test */
    public function rejectUnknownLevels()
    {
        $this->expectException(\Sensorario\Yaphlo\Exceptions\WrongLevelException::class);
        $this->expectExceptionMessage('Oops! Wrong level Exception!!');

        $content = [
            'foo' => 'bar'
        ];

        $message = new Message();
        $message->setContent($content);
        $message->setLevel('wrong');
    }

    /** @test */
    public function logAndForceDateTimeValue()
    {
        $content = [
            'foo' => 'bar'
        ];

        $currentDateTime = new \DateTime('tomorrow');

        $datetime = $currentDateTime->format('[Y-m-d H:i:s]');

        $renderedContent = <<<JSON
        $datetime [INFO] {
        $datetime [INFO]     "foo": "bar"
        $datetime [INFO] }
        JSON;

        $message = new Message();
        $message->forceDateTime($currentDateTime);
        $message->setContent($content);
        $message->setLevel(Message::LEVEL_INFO);

        $this->assertEquals($renderedContent, $message->render());
    }

    /** @test */
    public function hasFourLevelsOfLogging()
    {
        $message = new Message();
        $this->assertEquals([
            'INFO',
            'WARNING',
            'ERROR',
            'FATAL',
        ], $message->levelMap());
    }

    /** @test */
    public function inverseLevelArray()
    {
        $message = new Message();
        $this->assertEquals([
            'INFO' => 0,
            'WARNING' => 1,
            'ERROR' => 2,
            'FATAL' => 3,
        ], $message->inverseMap());
    }

    /** @test */
    public function alwaysLogMessageWithSameLevelOfConfiguration()
    {
        $message = new Message();
        $message->setLevel(Message::LEVEL_ERROR);
        $this->assertTrue($message->isPrintableWithLevel(Message::LEVEL_ERROR));
    }

    /** @test */
    public function alwaysLogMessageWithLevelLowerThanConfiguredForLogs()
    {
        $message = new Message();
        $message->setLevel(Message::LEVEL_INFO);
        $this->assertTrue($message->isPrintableWithLevel(Message::LEVEL_ERROR));
    }

    /** @test */
    public function throwExceptionWhenverMessageLevelIsNotDefined()
    {
        $this->expectException(\Sensorario\Yaphlo\Exceptions\MissingLevelException::class);
        $message = new Message();
        $this->assertTrue($message->isPrintableWithLevel(Message::LEVEL_INFO));
    }

    /** @test */
    public function neverLogMessagesWithLevelSuperiorOfConfiguredForLogs()
    {
        $message = new Message();
        $message->setLevel(Message::LEVEL_ERROR);
        $this->assertFalse($message->isPrintableWithLevel(Message::LEVEL_INFO));
    }

    /** @test */
    public function cathegorizeMessageWithChannels()
    {
        $content = [
            'foo' => 'bar'
        ];

        $currentDateTime = new \DateTime();

        $datetime = $currentDateTime->format('[Y-m-d H:i:s]');

        $renderedContent = <<<JSON
        $datetime [INFO] [something] {
        $datetime [INFO] [something]     "foo": "bar"
        $datetime [INFO] [something] }
        JSON;

        $message = new Message();
        $message->setContent($content);
        $message->setLevel(Message::LEVEL_INFO);
        $message->setChannel('something');

        $this->assertEquals($renderedContent, $message->render());
    }

    /** @test */
    public function provideChannelWithGetter()
    {
        $content = [
            'foo' => 'bar'
        ];

        $message = new Message();
        $message->setContent($content);
        $message->setLevel(Message::LEVEL_INFO);
        $message->setChannel('something');

        $this->assertEquals('something', $message->getChannel());
    }
}
