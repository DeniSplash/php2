<?php

namespace GeekBrains\LevelTwo\Blog\UniTests\Commands;

use PHPUnit\Framework\TestCase;
use GeekBrains\LevelTwo\Blog\Commands\Arguments;
use GeekBrains\LevelTwo\Blog\Exceptions\ArgumentException;

class ArgumentsTest extends TestCase
{
    public function testItReturnsArgumentsValueByName(): void
    {

        $arguments = new Arguments(['some_key' => 'some_value']);

        $value = $arguments->get('some_key');

        $this->assertEquals('some_value', $value);
    }

    public function testItThrowsAnExceptionWhenArgumentIsAbsent(): void
    {

        $arguments = new Arguments([]);

        $this->expectException(ArgumentException::class);

        $this->expectExceptionMessage("Аргумент не найден: some_key");

        $arguments->get('some_key');
    }

    public function argumentsProvider(): iterable
    {
        return [
            ['some_string', 'some_string'],
            [' some_string', 'some_string'],
            [' some_string ', 'some_string'],
            [123, '123'],
            [12.3, '12.3'],
        ];
    }

    /**
     * @dataProvider argumentsProvider
     */
    public function testItConvertsArgumentsToStrings($inputValue, $expectedValue): void
    {
        $arguments = new Arguments(['some_key' => $inputValue]);
        $value = $arguments->get('some_key');
        $this->assertEquals($expectedValue, $value);
    }
}