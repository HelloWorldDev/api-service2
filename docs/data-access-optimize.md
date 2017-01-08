## Being后端数据访问优化

我们后端一直使用的是Laravel框架, 在访问数据的时候, 都是查询主库.
在业务达到一定程度的时候, 数据库必然成为瓶颈.

因此, 我们计划优化数据库的访问, 增加系统整体的并发.

整体的方案如下:

一、数据库主写从读<br>
二、数据库缓存

详细的方案如下:

1. 配置laravel的数据库主写从读
2. 集成angejia/pea库以缓存数据库查询
3. 改进angejia/pea库并定制自己的缓存策略
4. 使用es优化复杂的查询

详细方案时间规划表:

| 功能                                   | 时间      |
| :---                                  | :----    |
| 配置laravel的数据库主写从读              | 0.5 day |
| 整理laravel主写从读中的注意事项           | 0.5 day |
| 集成angejia/pea库以缓存数据库查询         | 0.5 day |
| 整理angejia/pea库中的注意事项             |  0.5 day |
| 测试并整理集成pea前后的系统负载能力         |  1 day |
| 改进优化angejia/pea库并定制自己的缓存策略   | 5 days |
| 搭建redis的cluster测试集群               | 0.5 day |
| 搭建es集群                              | 1 day |
| 集成cviebrock/laravel-elasticsearch库   | 0.5 day |
| 整理es的使用规范                         | 0.5 day |

有关es部分的功能开发因为业务关系, 暂时不必集成.

总计时间为8.5天.(不包括es部分所需的2天)



### 配置laravel的数据库主写从读

[laravel的主从配置参考wiki](https://laravel.com/docs/5.1/database)

根据配置文件创建连接的php文件是Illuminate\Database\Connectors\ConnectionFactory.php

```
配置一读一写
'mysql' => [
    'read' => [
        'host' => '192.168.1.1',
    ],
    'write' => [
        'host' => '196.168.1.2'
    ],
    'driver'    => 'mysql',
    'database'  => 'database',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]
配置多读多写
'mysql' => [
    'read' => [
        'host' => ['192.168.1.1', '192.168.1.2'],
    ],
    'write' => [
        'host' => ['192.168.1.3', '192.168.1.4'],
    ],
    'driver'    => 'mysql',
    'database'  => 'database',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
],
或者(多读多写):
'mysql' => [
    'driver'    => 'mysql',
    'read' => [
        ['host' => '127.0.0.1', 'port' => '3306'],
        ['host' => '127.0.0.1', 'port' => '3307'],
    ],
    'write' => [
        ['host' => '127.0.0.1', 'port' => '3306']
    ],
    //'host'      => env('DB_HOST', 'localhost'),
    //'port'      => env('DB_PORT', 3306),
    'database'  => env('DB_DATABASE', 'forge'),
    'username'  => env('DB_USERNAME', 'forge'),
    'password'  => env('DB_PASSWORD', ''),
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => env('DB_PREFIX', ''),
    'timezone'  => env('DB_TIMEZONE', '+00:00'),
    'strict'    => false,
],
```

配置多读有一点不好的地方就是, 创建连接的时候, 都是创建一个读一个写的连接, 不管你是否使用了读库或写库.


注意事项:

1. 插入记录并获取自增ID是没有问题的.
2. 非事务中, 先插入再读取, 可能读到从库而导致读不到数据. 且使用DB::connection()可能造成事务混乱.
3. 事务中, Laravel能保证所有的语句执行在主库上.

详细说明:

#### 插入记录并获取自增ID是没有问题的, 因为两个操作是在同一个connection中进行的, 其方法为processInsertGetId().

#### 非事务中, 先插入再读取, 可能读到从库而导致读不到数据. 且使用DB::connection()可能造成事务混乱.

强制读主库在Laravel中使用的方式是

```
User::onWriteConnection()->find(1)
(wiki: https://laravel.com/api/5.1/Illuminate/Database/Eloquent/Model.html#method_onWriteConnection)
```

请勿使用下面的方式实现强制使用主库或者从库, 可能会造成意想不到的错误.

```
User::on('mysql::write')->find(1)
或
DB::connection('mysql::write')->select('select * from users where id = 1');
(Wiki: https://laracasts.com/discuss/channels/laravel/i-have-a-question-about-db-readwrite?page=1)
```

例如, 使用DB::connection可能造成事务跑在从库, 其测试方案如下:

```
测试代码:
public function testBasicExample()
{
    $users = User::on('mysql::read')->get()->toArray();
    print_r($users);
    \DB::transaction(function () {
        //User::insert(['username' => 'user' . rand(1000, 9999), 'password' => rand(1000, 9999)]);
        for ($i = 0; $i < 10; ++$i) {
            $users = User::get()->toArray();
            print_r($users);
        }
        User::where('username', 'like', 'user%')->delete();
    });

    $this->visit('/')
         ->see('Lumen.');
}
主库输出:
2017-01-07T14:27:15.769617Z	    8 Connect	root@172.17.0.1 on test using TCP/IP
2017-01-07T14:27:15.771463Z	    8 Prepare	set names 'utf8' collate 'utf8_unicode_ci'
2017-01-07T14:27:15.771983Z	    8 Execute	set names 'utf8' collate 'utf8_unicode_ci'
2017-01-07T14:27:15.772777Z	    8 Close stmt
2017-01-07T14:27:15.773233Z	    8 Prepare	set time_zone="+00:00"
2017-01-07T14:27:15.773826Z	    8 Execute	set time_zone="+00:00"
2017-01-07T14:27:15.774673Z	    8 Close stmt
2017-01-07T14:27:15.775128Z	    8 Prepare	set session sql_mode=''
2017-01-07T14:27:15.775620Z	    8 Execute	set session sql_mode=''
2017-01-07T14:27:15.776521Z	    8 Close stmt
2017-01-07T14:27:15.787445Z	    8 Quit
从库输出:
2017-01-07T14:27:15.780623Z	    6 Connect	root@172.17.0.1 on test using TCP/IP
2017-01-07T14:27:15.782315Z	    6 Prepare	set names 'utf8' collate 'utf8_unicode_ci'
2017-01-07T14:27:15.782844Z	    6 Execute	set names 'utf8' collate 'utf8_unicode_ci'
2017-01-07T14:27:15.783604Z	    6 Close stmt
2017-01-07T14:27:15.784126Z	    6 Prepare	set time_zone="+00:00"
2017-01-07T14:27:15.784599Z	    6 Execute	set time_zone="+00:00"
2017-01-07T14:27:15.785406Z	    6 Close stmt
2017-01-07T14:27:15.785834Z	    6 Prepare	set session sql_mode=''
2017-01-07T14:27:15.786322Z	    6 Execute	set session sql_mode=''
2017-01-07T14:27:15.787299Z	    6 Close stmt
2017-01-07T14:27:15.791590Z	    6 Prepare	select * from `user`
2017-01-07T14:27:15.792072Z	    6 Execute	select * from `user`
2017-01-07T14:27:15.793150Z	    6 Close stmt
2017-01-07T14:27:15.794412Z	    6 Query	START TRANSACTION
2017-01-07T14:27:15.795456Z	    6 Prepare	select * from `user`
2017-01-07T14:27:15.795963Z	    6 Execute	select * from `user`
2017-01-07T14:27:15.796956Z	    6 Close stmt
2017-01-07T14:27:15.797601Z	    6 Prepare	select * from `user`
2017-01-07T14:27:15.798158Z	    6 Execute	select * from `user`
2017-01-07T14:27:15.799328Z	    6 Close stmt
2017-01-07T14:27:15.799839Z	    6 Prepare	select * from `user`
2017-01-07T14:27:15.800444Z	    6 Execute	select * from `user`
2017-01-07T14:27:15.801827Z	    6 Close stmt
2017-01-07T14:27:15.802396Z	    6 Prepare	select * from `user`
2017-01-07T14:27:15.803035Z	    6 Execute	select * from `user`
2017-01-07T14:27:15.804277Z	    6 Close stmt
2017-01-07T14:27:15.804854Z	    6 Prepare	select * from `user`
2017-01-07T14:27:15.805448Z	    6 Execute	select * from `user`
2017-01-07T14:27:15.806723Z	    6 Close stmt
2017-01-07T14:27:15.807403Z	    6 Prepare	select * from `user`
2017-01-07T14:27:15.808116Z	    6 Execute	select * from `user`
2017-01-07T14:27:15.809240Z	    6 Close stmt
2017-01-07T14:27:15.809825Z	    6 Prepare	select * from `user`
2017-01-07T14:27:15.810276Z	    6 Execute	select * from `user`
2017-01-07T14:27:15.811313Z	    6 Close stmt
2017-01-07T14:27:15.811898Z	    6 Prepare	select * from `user`
2017-01-07T14:27:15.812349Z	    6 Execute	select * from `user`
2017-01-07T14:27:15.814384Z	    6 Close stmt
2017-01-07T14:27:15.815551Z	    6 Prepare	select * from `user`
2017-01-07T14:27:15.817529Z	    6 Execute	select * from `user`
2017-01-07T14:27:15.818901Z	    6 Close stmt
2017-01-07T14:27:15.819518Z	    6 Prepare	select * from `user`
2017-01-07T14:27:15.820042Z	    6 Execute	select * from `user`
2017-01-07T14:27:15.821221Z	    6 Close stmt
2017-01-07T14:27:15.821849Z	    6 Prepare	delete from `user` where `username` like ?
2017-01-07T14:27:15.822299Z	    6 Execute	delete from `user` where `username` like 'user%'
2017-01-07T14:27:15.823426Z	    6 Close stmt
2017-01-07T14:27:15.823859Z	    6 Query	COMMIT
2017-01-07T14:27:15.833552Z	    6 Quit
```

如果想使用原生SQL强制在主库上读取数据, 可以直接使用DB::select('', [], false)函数

```
public function select($query, $bindings = [], $useReadPdo = true){}
```

#### 事务中, Laravel能保证所有的语句执行在主库上.

```
主要的实现代码在ConnectionFactory类中
protected function createReadWriteConnection(array $config)
{
    $connection = $this->createSingleConnection($this->getWriteConfig($config));

    return $connection->setReadPdo($this->createReadPdo($config));
}
首先创建写连接, 然后创建读连接.
然后在使用select函数时
public function select($query, $bindings = [], $useReadPdo = true)
{
    return $this->run($query, $bindings, function ($me, $query, $bindings) use ($useReadPdo) {
        if ($me->pretending()) {
            return [];
        }

        // For select statements, we'll simply execute the query and return an array
        // of the database result set. Each element in the array will be a single
        // row from the database table, and will either be an array or objects.
        $statement = $this->getPdoForSelect($useReadPdo)->prepare($query);

        $statement->execute($me->prepareBindings($bindings));

        return $statement->fetchAll($me->getFetchMode());
    });
}
调用了getPdoForSelect(), 该函数如果发现当前处于事务状态($this->transactions >= 1), 则返回写连接.
```



### 集成angejia/pea库以缓存数据库查询

集成angejia之前, 我们先完善redis的配置, 修改config/database.php中redis这个key的值, 并执行

```
composer require illuminate/redis:^5.1
```

至此, redis安装完毕.

Laravel中一般使用Predis, 其配置多样及复杂, wiki地址为[https://github.com/nrk/predis](https://github.com/nrk/predis).

在Laravel中创建Redis连接的代码为:

```
RedisServiceProvider类:
public function register()
{
    $this->app->singleton('redis', function ($app) {
        return new Database($app['config']['database.redis']);
    });
}
Illuminate\Redis\Database类:
public function __construct(array $servers = [])
{
    $cluster = Arr::pull($servers, 'cluster');

    $options = (array) Arr::pull($servers, 'options');

    if ($cluster) {
        $this->clients = $this->createAggregateClient($servers, $options);
    } else {
        $this->clients = $this->createSingleClients($servers, $options);
    }
}

protected function createAggregateClient(array $servers, array $options = [])
{
    return ['default' => new Client(array_values($servers), $options)];
}

protected function createSingleClients(array $servers, array $options = [])
{
    $clients = [];

    foreach ($servers as $key => $server) {
        $clients[$key] = new Client($server, $options);
    }

    return $clients;
}

public function command($method, array $parameters = [])
{
    return call_user_func_array([$this->clients['default'], $method], $parameters);
}
```

从command函数中我们可以看出, 如果是非cluster模式, 那么使用的就只有default这个配置的连接.

下面我们重点关注, Predis中cluster的配置, 及实现的细节.

#### 方案一: 多节点, 客户端分片

```
config/database.php
'redis' => [
    'cluster' => true,
    'node1' => [
        'host'     => env('REDIS_HOST', '127.0.0.1'),
        'port'     => env('REDIS_PORT', 6379),
        'database' => env('REDIS_DATABASE', 0),
        'password' => env('REDIS_PASSWORD', null),
    ],
    'node2' => [
        'host'     => env('REDIS_HOST', '127.0.0.1'),
        'port'     => env('REDIS_PORT', 6380),
        'database' => env('REDIS_DATABASE', 0),
        'password' => env('REDIS_PASSWORD', null),
    ],
],
参考自:
$parameters = ['tcp://10.0.0.1', 'tcp://10.0.0.2', 'tcp://10.0.0.3'];
$client = new Predis\Client($parameters);
```

#### 方案二: 多节点, Redis服务端进行分片, 实现为RedisCluster

```
config/database.php
'redis' => [
    'cluster' => true,
    'options' => ['cluster' => 'redis'],
    'node1' => [
        'host'     => env('REDIS_HOST', '127.0.0.1'),
        'port'     => env('REDIS_PORT', 6379),
        'database' => env('REDIS_DATABASE', 0),
        'password' => env('REDIS_PASSWORD', null),
    ],
    'node2' => [
        'host'     => env('REDIS_HOST', '127.0.0.1'),
        'port'     => env('REDIS_PORT', 6380),
        'database' => env('REDIS_DATABASE', 0),
        'password' => env('REDIS_PASSWORD', null),
    ],
],
参考自:
$parameters = ['tcp://10.0.0.1', 'tcp://10.0.0.2', 'tcp://10.0.0.3'];
$options    = ['cluster' => 'redis'];
$client = new Predis\Client($parameters, $options);
```

下面再额外说明一下另外两种配置:

#### Replication

常见的Master-Slave模式, 一个Master, 多个Slave

```
congig/database.php
'redis' => [
    'cluster' => true,
    'options' => ['replication' => true],
    'node1' => [
        'host'     => env('REDIS_HOST', '127.0.0.1'),
        'port'     => env('REDIS_PORT', 6379),
        'database' => env('REDIS_DATABASE', 0),
        'password' => env('REDIS_PASSWORD', null),
        'alias' => 'master',
    ],
    'node2' => [
        'host'     => env('REDIS_HOST', '127.0.0.1'),
        'port'     => env('REDIS_PORT', 6380),
        'database' => env('REDIS_DATABASE', 0),
        'password' => env('REDIS_PASSWORD', null),
    ],
],
参考自:
$parameters = ['tcp://10.0.0.1?alias=master', 'tcp://10.0.0.2', 'tcp://10.0.0.3'];
$options    = ['replication' => true];
$client = new Predis\Client($parameters, $options);
```

#### Sentinel

哨兵模式, 需要配置多个哨兵的服务器地址, 然后Predis会自动查询集群中的Master服务器和Slave服务器

```
config/database.php
'redis' => [
    'cluster' => true,
    'options' => ['replication' => 'sentinel', 'service' => 'mymaster'],
    'node1' => [
        'host'     => env('REDIS_HOST', '127.0.0.1'),
        'port'     => env('REDIS_PORT', 5380),
        'database' => env('REDIS_DATABASE', 0),
        'password' => env('REDIS_PASSWORD', null),
    ],
    'node2' => [
        'host'     => env('REDIS_HOST', '127.0.0.1'),
        'port'     => env('REDIS_PORT', 5381),
        'database' => env('REDIS_DATABASE', 0),
        'password' => env('REDIS_PASSWORD', null),
    ],
],
参考自:
$sentinels = ['tcp://10.0.0.1', 'tcp://10.0.0.2', 'tcp://10.0.0.3'];
$options   = ['replication' => 'sentinel', 'service' => 'mymaster'];
$client = new Predis\Client($sentinels, $options);
```

其中需要说明的是service字段, 其含义为master-group-name, 下面这张图很好的解释了group的概念,
即sentinel集群能监控多个redis集群, 每个集群分别需要不同的组名.

```
sentinel monitor [master-group-name] [ip] [port] [quorum]
```

[![master-group-name含义](http://nos.netease.com/knowledge/f66a5bfe-51e3-4c75-b694-2c6e1d1b2e4b)]


#### 集成pea

安装:

```
composer require angejia/pea:dev-master
```

配置:

```
config/database.php
'redis' => [
    'cluster' => false,
        'default' => [
        'host'     => '127.0.0.1',
        'port'     => 6379,
        'database' => 0,
    ],
    'pea' => [
        'host'     => '127.0.0.1',
        'port'     => 6379,
        'database' => 2,
    ],
]
如果没有指定pea专用配置，则自动选用默认配置。
```

使用:

```
app.php
$app->register(Angejia\Pea\ServiceProvider::class);

UserModel.php
class UserModel extends \Angejia\Pea\Model
{
    protected $needCache = true;
}
```

