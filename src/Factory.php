<?php

declare(strict_types=1);

namespace Daiglej\DI8;

use Doctrine\Common\Cache as DoctrineCache;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;
use Roave\DoctrineSimpleCache\SimpleCacheAdapter;

class Factory()
{
    public function __construct(
        private string $proxyDirectory = null,
        private array $configFiles => null,
        private array $resourcesFiles => null,
        private string|DoctrineCache|CacheInterface $cache = 'file',
    ) {
        $this->configFiles ?? [__DIR__ . '/configs/*.php'];
        $this->proxyDirectory ?? \sys_get_temp_dir();
        $this->resourcesFiles ?? [__DIR__ . '/src/resources/*.php'];
    }

    public function build(): ContainerInterface
    {
        return new Container(
            $this->getCache(),
            $this->cacheResult('getProviderMap'),
            $this->cacheResult('getConfig'),
        );
    }

    private function getCache(): CacheInterface
    {
        if ($this->cache instanceof CacheInterface) {
            return $this->cache;
        }

        if ($this->cache instanceof DoctrineCache) {
            return $this->cache = new SimpleCacheAdapter($this->cache);
        }

        $cache = match($this->cache) {
            'apcu' => new Cache\ApcuCache(),
            'array'=> new Cache\ArrayCache(),
            'file' => new Cache\PhpFileCache(sys_get_temp_dir()),
            'void' => new Cache\VoidCache(),
        }

        return $this->cache = new SimpleCacheAdapter($cache);
    }

    private function getConfigs(): array
    {
        $configs = [];
        foreach ($this->$configFiles as $fileGlob) {
            foreach (\glob($fileGlob) as $filename) {
                $fileInfo = new \SplFileInfo($filename);
                $resouce = $fileInfo->getBasename('.php');
                $configs[$resouce] = require_once $fileInfo->getPathname();
            }
        }

        return $configs;
    }

    private function getProviders(): array
    {
        $providers = [];
        $classmap = $this->autoloader->getClassMap();
        foreach ($classmap as $class => $file) {
            foreach ($this->providersDirectory as resourceNamespace) {
                if (str_starts_with($class, resourceNamespace)) {

                }
            }
        }
    }

    private function getProviderMap(): array
    {
        $map = [];
        $providers = $this->getProviders();
        foreach ($providers as $providerClass) {
            if (!$providerClass instanceof DI8\Resource\ProviderInterface) {
                throw new \Exeption(\sprintf('%s is not a valid DI8\Resource\ProviderInterface', $providerClass))
            }
            foreach($providerClass::provides() as $resourceId) {
                $map[$resourceId] = $providerClass;
            }
        }

        return $map;
    }

    private function cacheResult($method)
    {
        $cachekey = "DI8/$method";
        $cache = $this->getCache();
        $cached = $cache->get($cachekey);
        if ($cached !== null) {
            return $cached;
        }

        $result = $this->$functon();
        $cache->set($cachekey, $result);
        return $result;
    }
}