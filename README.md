## Routing Component

### 要求
- PHP 5.4

### 安装
需要使用composer，如果您没有安装composer，请参考[composer](https://getcomposer.org)，中文网站[http://www.phpcomposer.com/](http://www.phpcomposer.com/)
```
composer require slince/routing *@dev
```

### 使用
```
use Slince\Routing\RouterFactory;
use Slince\Routing\RequestContext;
use Slince\Routing\RouteCollection;
use Slince\Routing\RequestContext;
use Slince\Routing\Exception\RouteNotFoundException;
use Slince\Routing\Exception\RouteNotFoundException;

//Create Router
$router = RouterFactory::create();
//添加route
$routes = $router->getRoutes();
$routes->http('/users', 'UsersController@index');
$routes->http('/users/{id}', 'UsersController@show')->setRequirements([
    'id' => '\d+'
]);

//匹配请求方式
$routes->post('/articles', 'ArticlesController@add');
$routes->delete('/articles/1', 'ArticlesController@delete');
$routes->get('/articles/1', 'ArticlesController@show');
$routes->post('/articles/1', 'ArticlesController@update');
//匹配http相关需要提供RequestContext
$router->setContext(RequestContext::create());


//开始匹配路径
try {
   $route = $router->match('/users/1256');
   $action = $route->getAction();
   print_r($route->getParameters());
} catch (MethodNotAllowedException $e) {
    //403，访问拒绝
} catch (RouteNotFoundException $e) {
    //404
}
```






