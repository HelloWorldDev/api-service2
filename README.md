# Being Api Services

being base services include helper, live, resource and so on.


## Helper

```
class HelperService
{
    public static function limit($request, $default = 10, $max = 100, $key = 'limit'){}
}
```

## Resource

```
class ResourceService
{
    public static function url2key($url){}
}
```