<?php

declare(strict_type=1);

namespace Daiglej\DI8\Resource;

class ProviderInterface
{
    /**
     * Returns the list of glob matcher, that his provider can build
     * @return array|sting[]
     */
    public static function provides(): array;


    public static function isSingleton(): bool;
}