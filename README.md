# Cache component

缓存机制是很常见的在现在的web应用中，组件致力于使用简单且完善的逻辑实现缓存在项目中的应用。目前支持的缓存形式有file，apc，memcache

### 安装

在composer.json中添加

    {
        "require": {
            "slince/cache": "dev-master@dev"
        }
    }

### 用法

    $fileHandler = new Slince\Cache\Handler\FileHandler('./src/');
    $cache = new Slince\Cache\Cache($fileHandler);

    //设置默认有效时间为两小时，默认是一小时
    $cache->setDuration(7200);

    //设置记录
    $cache->set('key1', 'val1');

    //添加记录
    $cache->set('key2', 'val2');

    //删除记录
    $cache->delete('key1');

    //判断是否存在
    $cache->exists('key1');

    //清除所有
    $cache->flush();

你也可以在添加记录时单独设置缓存时间，只要add和set方法提供第三个参数即可，但不会影响全局的默认时间；所有的缓存时间单位都是秒数。