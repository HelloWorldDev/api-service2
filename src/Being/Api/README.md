## Introduction

```
$auth = new \Being\Api\Auth('app_id', 'app_secret');
$request = new \Being\Api\Request($auth, 'GET', 'http://www.being.com/');
$request->setQuery(['get_filed' => 'value'])
        ->setParam(['post_filed' => 'value'])
        ->setAsync(false)
        ->setTimeout(3)
        ->setBody('http_body')
        ->setLogFile('http.log');
$response = $request->send();
$response->isSuccess();
$response->getData();
$response->getMessage();
```