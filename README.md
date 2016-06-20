# Config component

如果您的应用需要从不同类型的文件中读取配置并且需要将配置写回到文件中，那么该组件可以帮助您轻松实现该功能；组件目前支持的文件类型有php、json、ini;在后续的版本中更多的文件类型将会被支持。

###安装

在composer.json中添加

    {
        "require": {
            "slince/config": "*"
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
    $config = new Slince\Config\Config;
    $config->load(__DIR__ . '/config/config.php');
    $config->load('config.json');
    $config->load('config.ini');
    
    //获取参数,键值不存在时会得到默认值，null；get方法支持自定义默认值
    echo $config->get('key1');
    echo $config['key2'];
    
    //设置参数
    $config->set('key7', 'val7');
    $config['key8'] = 'val8';
    
    //判断键值是否存在
    if ($config->exists('key9')) {
        //***
    }
    if (config($data['key9'])) {
        //***
    }
    
    //删除键值
    $config->delete('key8');
    unset($config['key8']);
    
    //将配置写回到config.php中
    $config->dump(__DIR__ . '/config/config-dump.php');

    //清空配置
    $config->flush();
    