# Config component

如果您的应用需要从不同类型的文件中读取配置并且要将配置写回到文件中，那么该组件可以帮助您轻松实现该功能；目前支持的文件类型有php、json、ini;在后续的版本中更多的文件类型将会被支持。

###安装

在composer.json中添加

    {
        "require": {
            "slince/config": "dev-master@dev"
        }
    }

### 用法
    
    //config.php
    return array(
        'key1' => 'val1',
        'key2' => 'val2'
    );

    //config.json
    {
        "key3": "val3",
        "key4": "val4"
    }

    //config.ini
    key5 = val5
    key6 = val6

    //client.php
    $repository = new Slince\Config\Repository;
    $repository->merge(new Slince\Config\Parser\PhpArrayParser('config.php'));
    $repository->merge(new Slince\Config\Parser\JsonParser('config.json'));
    $repository->merge(new Slince\Config\Parser\IniParser('config.ini'));
    
    //获取参数
    echo $repository->get('key1');
    echo $repository['key2'];
    //设置参数
    $repository->set('key7', 'val7');
    $repository['key8'] = 'val8';
    
    //将配置写回到config.php中
    