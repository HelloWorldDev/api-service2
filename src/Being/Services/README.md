## Services

Codes contain framework dependencies should put in directory "App"

代码中包含框架(例如laravel)代码的请放到APP目录下面, 抽象程度非常高的函数可以直接放在Services目录下。


## App

```
class AppService
{
    public static function limit($request, $default = 10, $max = 100, $key = 'limit'){}
    public static function isiOSAppClient(){}
    public static function isAndroidAppClient(){}
}
```

## Resource

```
class ResourceService
{
    public static function url2key($url){}
}
```

## Prof

```
class ProfService
{
    public static function begin(){}
    public static function end(){}
    public static function prof(callable $callback, $catchException = true){}
}
```
