<?php

namespace Sensorario\Yaphlo\Tests\Services;

use Sensorario\Yaphlo\Services\RowBuilder\RowBuilder;
use Sensorario\Yaphlo\Services\RowBuilder\Exceptions\{
        MissingLineException,
        MissingLevelException,
        MissingChannelException,
    };

class RowBuilderTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    public function missingLevelThrowsAnException()
    {
        $this->expectException(MissingLineException::class);
        $this->expectExceptionMessage('Oops! Missing line!');

        $builder = new RowBuilder();
        $rendered = $builder->rendered();
        $this->assertEquals('', $rendered);
    }

    /** @test */
    public function addLine()
    {
        $this->expectException(MissingLevelException::class);
        $this->expectExceptionMessage('Oops! Missing level!');

        $builder = new RowBuilder();
        $builder->addLine('foo');
        $rendered = $builder->rendered();
        $this->assertEquals('foo', $rendered);
    }

    /** @test */
    public function addLineAndLevel()
    {
        $this->expectException(MissingChannelException::class);
        $this->expectExceptionMessage('Oops! Missing channel!');

        $builder = new RowBuilder();
        $builder->addLine('foo');
        $builder->addLevel('two');
        $rendered = $builder->rendered();
        $this->assertEquals('[two] foo', $rendered);
    }

    /** @test */
    public function addLineLevelAndChannel()
    {
        $builder = new RowBuilder();
        $builder->addLine('one');
        $builder->addLevel('two');
        $builder->addChannel('three');
        $rendered = $builder->rendered();
        $datetime = (new \DateTime('now'))->format('Y-m-d H:i:s');
        $this->assertEquals('['.$datetime.'] [two] [three] one', $rendered);
    }

    /** @test */
    public function addLineLevelChannelAndDatetime()
    {
        $builder = new RowBuilder();
        $builder->addLine('one');
        $builder->addLevel('two');
        $builder->addChannel('three');
        $builder->addDateTime(new \DateTime('10/09/1982 17:12:45'));
        $rendered = $builder->rendered();
        $this->assertEquals('[1982-10-09 17:12:45] [two] [three] one', $rendered);
    }

    /** @test */
    public function resetEraseAllTheRowContents()
    {
        $datetime = new \DateTime('now');
        $builder = new RowBuilder();
        $builder->addLine('one');
        $builder->addLevel('two');
        $builder->addChannel('three');
        $builder->addDateTime($datetime);
        $builder->reset();
        $rendered = $builder->rendered();
        $this->assertEquals((new \DateTime('now'))->format('[Y-m-d H:i:s]') . ' [INFO]', $rendered);
    }
}
