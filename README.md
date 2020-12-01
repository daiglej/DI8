# DI8
A DI Container build from the ground up for PHP8 features.

## Resources type:
    on-demand: default
    singleton:
    alias: Like singleton, but will return the same instance on all alias.
    pre-load: like alias, but the resouce is created as soon as added to the DIC
    pre-load-caced: like preload, but will get batch loaded from cache. Mostly usefull for configurations.
    
## lazyness
    boolean, when true instead of injecting the resource, a lazy container is injected instead.
    should lazyness be requested by the consumer?
    
## Usage
```
    $autoload = require_once __DIR__ . '/vendor/autoload.php'    

    $dic = (new \Daiglej\DI8\Factory(
        configFiles: [__DIR__ . '/configs/*.php']
        resourceFiles: [__DIR__ . '/resources/*.php],
        proxyDirectory: \sys_get_temp_dir()
    ))->build();

    $cache = $dic->get(\Psr\Cache\CacheItemInterface::class);
```

To Write lazy proxies (and the cache if using file) :
 
 ```
 $dic = (new \Daiglej\DI8\Factory(
         configFiles: [__DIR__ . '/configs/*.php']
         resourceFiles: [__DIR__ . '/resources/*.php],
         proxyDir: \sys_get_temp_dir()
     ))->writeProxy();
```