<?php

namespace Sensorario\Yaphlo;

use Sensorario\Yaphlo\Services\RowBuilder;

class MessageTest extends \PHPUnit\Framework\TestCase
{
    private $builder;

    public function setUp(): void
    {
        $this->builder = $this
            ->getMockBuilder(RowBuilder::class)
            ->getMock();
    }

    /** @test */
    public function isAnEmptyArrayIfNoItemWerePassedToConstructor()
    {
        $message = new Message($this->builder);
        $this->assertEquals([], $message->content());
    }

    /** @test */
    public function contentIsTheSameSentViaConstructor()
    {
        $content = [
            'foo' => 'bar'
        ];
        $message = new Message($this->builder);
        $message->setContent($content);
        $this->assertEquals($content, $message->content());
    }

    /** @test */
    public function rowBuilderResetEachTimeRenderIsCalled()
    {
        $this->builder->expects($this->once())
            ->method('reset');

        $message = new Message($this->builder);
        $message->setContent(['foo' => 'bar']);
        $message->render();
    }

    /** @test */
    public function rowMustContainsDatetime()
    {
        $now = new \DateTime();

        $datetime = $now->format('Y-m-d H:i:s');

        $this->builder->expects($this->exactly(1))
            ->method('addDateTime')
            ->with($now);

        $this->builder->expects($this->exactly(1))
            ->method('addLevel')
            ->with(Message::LEVEL_INFO);

        $this->builder->expects($this->exactly(1))
            ->method('addChannel')
            ->with('channel A');

        $this->builder->expects($this->exactly(3))
            ->method('addLine');

        $this->builder->expects($this->exactly(3))
            ->method('rendered')
            ->withConsecutive([0], [1], [2])
            ->willReturnOnConsecutiveCalls(
                '[' . $datetime . '] [INFO] [channel A] {',
                '[' . $datetime . '] [INFO] [channel A]     "foo": "bar"',
                '[' . $datetime . '] [INFO] [channel A] }',
            );

        $message = new Message($this->builder);
        $message->setLevel(Message::LEVEL_INFO);
        $message->forceDateTime($now);
        $message->setContent(['foo' => 'bar']);
        $message->setChannel('channel A');
        $output = $message->render();

        $out = <<<OUT
        [$datetime] [INFO] [channel A] {
        [$datetime] [INFO] [channel A]     "foo": "bar"
        [$datetime] [INFO] [channel A] }
        OUT;

        $this->assertEquals($out, $output);
    }

    /** @test */
    public function renderEachLineWithDateAndTime()
    {
        $content = [
            'foo' => 'bar'
        ];

        $now = new \DateTime();

        $datetime = $now->format('[Y-m-d H:i:s]');

        $renderedContent = <<<JSON
        $datetime {
        $datetime     "foo": "bar"
        $datetime }
        JSON;

        $this->builder->expects($this->exactly(1))
            ->method('addDateTime')
            ->with($now);

        $this->builder->expects($this->once())
            ->method('addLevel')
            ->with(null);;

        $this->builder->expects($this->once())
            ->method('addChannel');

        $this->builder->expects($this->exactly(3))
            ->method('addLine');

        $this->builder->expects($this->exactly(3))
            ->method('rendered')
            ->withConsecutive([0], [1], [2])
            ->willReturnOnConsecutiveCalls(
                '' . $datetime . ' {',
                '' . $datetime . '     "foo": "bar"',
                '' . $datetime . ' }',
            );

        $message = new Message($this->builder);
        $message->forceDateTime($now);
        $message->setContent($content);
        $this->assertEquals($renderedContent, $message->render());
    }

    /** @test */
    public function displayLevelOnlyWhenRequested()
    {
        $content = [
            'foo' => 'bar'
        ];

        $now = new \DateTime();

        $datetime = $now->format('[Y-m-d H:i:s]');

        $renderedContent = <<<JSON
        $datetime [INFO] {
        $datetime [INFO]     "foo": "bar"
        $datetime [INFO] }
        JSON;

        $this->builder->expects($this->exactly(1))
            ->method('addDateTime')
            ->with($now);

        $this->builder->expects($this->exactly(1))
            ->method('addLevel')
            ->with(Message::LEVEL_INFO);

        $this->builder->expects($this->once())
            ->method('addChannel');

        $this->builder->expects($this->exactly(3))
            ->method('addLine');

        $this->builder->expects($this->exactly(3))
            ->method('rendered')
            ->withConsecutive([0], [1], [2])
            ->willReturnOnConsecutiveCalls(
                '' . $datetime . ' [INFO] {',
                '' . $datetime . ' [INFO]     "foo": "bar"',
                '' . $datetime . ' [INFO] }',
            );

        $message = new Message($this->builder);
        $message->forceDateTime($now);
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

        $message = new Message($this->builder);
        $message->setContent($content);
        $message->setLevel('wrong');
    }

    /** @test */
    public function logAndForceDateTimeValue()
    {
        $content = [
            'foo' => 'bar'
        ];

        $now = new \DateTime('tomorrow');

        $datetime = $now->format('[Y-m-d H:i:s]');

        $renderedContent = <<<JSON
        $datetime [INFO] {
        $datetime [INFO]     "foo": "bar"
        $datetime [INFO] }
        JSON;

        $this->builder->expects($this->exactly(1))
            ->method('addDateTime')
            ->with($now);

        $this->builder->expects($this->exactly(1))
            ->method('addLevel')
            ->with(Message::LEVEL_INFO);

        $this->builder->expects($this->once())
            ->method('addChannel')
            ->with(null);;

        $this->builder->expects($this->exactly(3))
            ->method('addLine');

        $this->builder->expects($this->exactly(3))
            ->method('rendered')
            ->withConsecutive([0], [1], [2])
            ->willReturnOnConsecutiveCalls(
                '' . $datetime . ' [INFO] {',
                '' . $datetime . ' [INFO]     "foo": "bar"',
                '' . $datetime . ' [INFO] }',
            );

        $message = new Message($this->builder);
        $message->forceDateTime($now);
        $message->setContent($content);
        $message->setLevel(Message::LEVEL_INFO);

        $this->assertEquals($renderedContent, $message->render());
    }

    /** @test */
    public function hasFourLevelsOfLogging()
    {
        $message = new Message($this->builder);
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
        $message = new Message($this->builder);
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
        $message = new Message($this->builder);
        $message->setLevel(Message::LEVEL_ERROR);
        $this->assertTrue($message->isPrintableWithLevel(Message::LEVEL_ERROR));
    }

    /** @test */
    public function alwaysLogMessageWithLevelLowerThanConfiguredForLogs()
    {
        $message = new Message($this->builder);
        $message->setLevel(Message::LEVEL_INFO);
        $this->assertTrue($message->isPrintableWithLevel(Message::LEVEL_ERROR));
    }

    /** @test */
    public function throwExceptionWhenverMessageLevelIsNotDefined()
    {
        $this->expectException(\Sensorario\Yaphlo\Exceptions\MissingLevelException::class);
        $message = new Message($this->builder);
        $this->assertTrue($message->isPrintableWithLevel(Message::LEVEL_INFO));
    }

    /** @test */
    public function neverLogMessagesWithLevelSuperiorOfConfiguredForLogs()
    {
        $message = new Message($this->builder);
        $message->setLevel(Message::LEVEL_ERROR);
        $this->assertFalse($message->isPrintableWithLevel(Message::LEVEL_INFO));
    }

    /** @test */
    public function cathegorizeMessageWithChannels()
    {
        $content = [
            'foo' => 'bar'
        ];

        $now = new \DateTime();

        $datetime = $now->format('[Y-m-d H:i:s]');

        $renderedContent = <<<JSON
        $datetime [INFO] [something] {
        $datetime [INFO] [something]     "foo": "bar"
        $datetime [INFO] [something] }
        JSON;

        $this->builder->expects($this->exactly(1))
            ->method('addDateTime')
            ->with($now);

        $this->builder->expects($this->exactly(1))
            ->method('addLevel')
            ->with(Message::LEVEL_INFO);

        $this->builder->expects($this->exactly(1))
            ->method('addChannel')
            ->with('something');

        $this->builder->expects($this->exactly(3))
            ->method('addLine');

        $this->builder->expects($this->exactly(3))
            ->method('rendered')
            ->withConsecutive([0], [1], [2])
            ->willReturnOnConsecutiveCalls(
                '' . $datetime . ' [INFO] [something] {',
                '' . $datetime . ' [INFO] [something]     "foo": "bar"',
                '' . $datetime . ' [INFO] [something] }',
            );

        $message = new Message($this->builder);
        $message->forceDateTime($now);
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

        $message = new Message($this->builder);
        $message->setContent($content);
        $message->setLevel(Message::LEVEL_INFO);
        $message->setChannel('something');

        $this->assertEquals('something', $message->getChannel());
    }
}
