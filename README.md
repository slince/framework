# Cache component

缓存机制是很常见的在现在的web应用中，改组件致力于用简单完善的逻辑实现缓存在项目中的应用。目前支持的缓存形式有file，apc，memcache,array(临时缓存，只对当前请求有效)

### 安装

在composer.json中添加
```
{
    "require": {
        "slince/cache": "dev-master@dev"
    }
}
```
### 用法
```
$fileCache = new Slince\Cache\FileCache('./tmp/');

//设置默认有效时间为两小时，默认是一小时
$fileCache->setDuration(7200);

//设置记录
$fileCache->set('key1', 'val1');

//添加记录
$fileCache->set('key2', 'val2');

//删除记录
$fileCache->delete('key1');

//判断是否存在
$fileCache->exists('key1');

//清除所有
$fileCache->flush();
```
你也可以在添加记录时单独设置缓存时间，只要add和set方法提供第三个参数即可，但不会影响全局的默认时间；所有的缓存时间单位都是秒数。