<?php

namespace Sensorario\Yaphlo;

class MessageTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    public function isAnEmptyArrayIfNoItemWerePassedToConstructor()
    {
        $message = new Message(new \DateTime);
        $this->assertEquals([], $message->content());
    }

    /** @test */
    public function contentIsTheSameSentViaConstructor()
    {
        $content = [
            'foo' => 'bar'
        ];
        $message = new Message(new \DateTime());
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

        $message = new Message($currentDateTime);
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

        $message = new Message($currentDateTime);
        $message->setContent($content);
        $message->setLevel(Message::LEVEL_INFO);

        $this->assertEquals($renderedContent, $message->render());
    }

    /** @test */
    public function rejectUnknownLevels()
    {
        $this->expectException(\Sensorario\Yaphlo\WrongLevelException::class);
        $this->expectExceptionMessage('Oops! Wrong level Exception!!');

        $content = [
            'foo' => 'bar'
        ];

        $currentDateTime = new \DateTime();
        $message = new Message($currentDateTime);
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
}
