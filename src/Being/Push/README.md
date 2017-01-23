## Push Service

// 使用Send对象是因为当前发送消息send方法是封装到Message方法里面的, 后面可能考虑会脱离出来.

```
use Being\Push\Message;
use Being\Push\Message\ApnsMessage;
use Being\Push\Message\BaiduMessage;
use Being\Push\Message\GcmMessage;
use Being\Push\Send;

$apnsMessage = (new ApnsMessage('apns_token', 'hello'))
    ->setCertificateFile('certificate_file')
    ->setEnv(Message::ENVIRONMENT_SANDBOX);

$gcmMessage = (new GcmMessage('user_token', 'hello'))
    ->setApiKey('api_key');

$baiduMessage = (new BaiduMessage('user_token', 'hello'))
    ->setApiKey('api_key')
    ->setApiSecret('api_secret');

(new Send())
    ->addMessage($apnsMessage)
    ->addMessage($gcmMessage)
    ->addMessage($baiduMessage)
    ->send();

```