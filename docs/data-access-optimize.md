## Being后端数据访问优化

我们后端一直使用的是Laravel框架, 在访问数据的时候, 都是查询主库.
在业务达到一定程度的时候, 数据库必然成为瓶颈.

因此, 我们计划优化数据库的访问, 增加系统整体的并发.

整体的方案如下:

一、数据库主写从读
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
```
