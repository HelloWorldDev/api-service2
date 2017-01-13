## Introduction

```
$httpCli = new HttpClient('http://endpoint/user');
$cli = new UserClient($httpCli);

$user = new User(...);
list($code, $data) = $cli->register($user);

// code in Code.php
// if code != 0, data is null, else is JSON object


```