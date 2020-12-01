<?php

declare(strict_types=1);

namespace Daiglej\DI8;

use Psr\SimpleCache\CacheInterface;

class Container implements \Psr\Container\Interface
{
    /**
     * Map resources to their
     * @var string[]
     */
    private array providersMap = [];

    public function __construct(
        private CacheInterface $cache,
        private array providersMap = [],
    ) {
        if ($item = $this->cache->get('proprovidersMap')) {
            $this->providersMap = $item->get();
        }
    }

    /**
     * Finds an entry of the  by its identifier and returns it.
     *
     * @throws \Psr\Container\NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws \Psr\Container\ContainerExceptionInterface Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get(string $id)
    {
        if ($this->cached[$id])) {
            return $this->cached[$id];
        }

        if (isset($this->singleton[$id])) {
            return $this->singleton[$id];
        }

        if (!$this->has($id)) {
            throw new \Psr\Container\NotFoundExceptionInterface(\sprintf('Unable to find provider for %s', $id));
        }
    }


    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has(string $id): bool
    {
        if (\isset($this->providersMap[$id])) {
            return true;
        }
        return false;
    }
}