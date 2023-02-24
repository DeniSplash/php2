<?php
namespace GeekBrains\Blog\UnitTests\Container;

use GeekBrains\LevelTwo\Blog\Container\DIContainer;
use PHPUnit\Framework\TestCase;

class DIContainerTest extends TestCase
{

    public function testItResolvesClassByContract(): void
    {
        $container = new DIContainer();

        $container->bind(
            UsersRepositoryInterface::class,
            InMemoryUsersRepository::class
        );

        $object = $container->get(UsersRepositoryInterface::class);

        $this->assertInstanceOf(
            InMemoryUsersRepository::class,
            $object
        );
    }

    public function testItReturnsPredefinedObject(): void
    {

        $container = new DIContainer();
        $container->bind(
            SomeClassWithParameter::class,
            new SomeClassWithParameter(42)
        );
        $object = $container->get(SomeClassWithParameter::class);

        $this->assertInstanceOf(
            SomeClassWithParameter::class,
            $object
        );

        $this->assertSame(42, $object->value());
    }

}