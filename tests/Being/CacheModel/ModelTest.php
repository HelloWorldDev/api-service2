<?php

namespace Tests\Being\CacheModel;

use Being\CacheModel\Model;
use Mockery as M;
use Mockery\MockInterface;
use Angejia\Pea\Cache;
use Angejia\Pea\Meta;
use Illuminate\Container\Container;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Database\Query\Processors\Processor;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Query\Expression;

class ModelTest extends TestCase
{
    /**
     * @var MockInterface
     */
    private $meta;

    /**
     * @var MockInterface
     */
    private $conn;

    /**
     * @var MockInterface
     */
    private $cache;

    public function setUp()
    {
        parent::setUp();

        // 构建数据库模拟层
        $conn = M::mock(ConnectionInterface::class);
        // 模拟连接 angejia 数据库
        $conn->shouldReceive('getDatabaseName')->andReturn('angejia');
        $conn->shouldReceive('getQueryGrammar')->andReturn(new Grammar);
        $conn->shouldReceive('getPostProcessor')->andReturn(new Processor);

        $this->conn = $conn;

        // 让所有 Model 使用我们伪造的数据库连接
        $resolver = M::mock(ConnectionResolverInterface::class);
        $resolver->shouldReceive('connection')
            ->andReturnUsing(function () {
                return $this->conn;
            });
        User::setConnectionResolver($resolver);

        // 模拟 Meta 服务
        $meta = M::mock(Meta::class);
        $meta->shouldReceive('prefix')
            // 查找 angejia.user 表主键缓存 key 前缀
            ->with('angejia', 'user')
            // 缓存 key 前缀全部使用空字符串 ''
            ->andReturn('');
        $meta->shouldReceive('prefix')
            // 查找 angejia.user 表主键缓存 key 前缀
            ->with('angejia', 'user', true)
            // 缓存 key 前缀全部使用空字符串 ''
            ->andReturn('');
        $this->meta = $meta;

        // 模拟 Cache 服务
        $cache = M::mock(Cache::class);
        $this->cache = $cache;

        // 注入依赖的服务
        $this->app->bind(Meta::class, function () {
            return $this->meta;
        });
        $this->app->bind(Cache::class, function () {
            return $this->cache;
        });
    }

    public function testModelTable()
    {
        $user = new User;
        $this->assertEquals('user', $user->table());
    }

    public function testModelNeedCache()
    {
        $user = new User;
        $this->assertTrue($user->needCache());
    }

    public function testOneCachedSimpleGet()
    {
        $this->cache->shouldReceive('get')
            // 查询 id 为 1 的缓存
            ->with([
                '3558193cd9818af7fe4d2c2f5bd9d00f',
            ])
            // 模拟全部命中缓存
            ->andReturn([
                '3558193cd9818af7fe4d2c2f5bd9d00f' => (object) [ 'id' => 1, 'name' => '海涛', ],
            ]);

        $dispatcher = M::Mock(Dispatcher::class);
        $dispatcher->shouldReceive('fire')->with('angejia.pea.get', ['table' => 'user', 'db' => 'angejia']);
        $dispatcher->shouldReceive('fire')->with('angejia.pea.hit.simple.1000', ['table' => 'user', 'db' => 'angejia']);
        $this->app->instance(Dispatcher::class, $dispatcher);

        // 查询 id 为 1 的记录，应该命中缓存
        $u1 = User::find(1);

        $this->assertEquals('海涛', $u1->name);
    }
}

class User extends Model
{
    protected $table = 'user';
    protected $needCache = true;
    public $timestamps = false;
}
