<?php

namespace Tests\Being\WeiboOpenApi;

use Being\WeiboOpenApi\WeiboClient;

abstract class ServiceProviderTestBase extends \PHPUnit_Framework_TestCase
{
    /**
     * Test get user info
     * success response from weibo, user info
    Array
    (
    [id] => 1769237827
    [idstr] => 1769237827
    [class] => 1
    [screen_name] => 宋晓锋SFeng
    [name] => 宋晓锋SFeng
    [province] => 11
    [city] => 1
    [location] => 北京 东城区
    [description] =>
    [url] =>
    [profile_image_url] => http://tva3.sinaimg.cn/crop.0.0.180.180.50/69746d43jw1e8qgp5bmzyj2050050aa8.jpg
    [profile_url] => lesserbear
    [domain] => lesserbear
    [weihao] =>
    [gender] => m
    [followers_count] => 233
    [friends_count] => 422
    [pagefriends_count] => 0
    [statuses_count] => 155
    [favourites_count] => 11
    [created_at] => Tue Jul 06 17:06:07 +0800 2010
    [following] =>
    [allow_all_act_msg] =>
    [geo_enabled] => 1
    [verified] =>
    [verified_type] => -1
    [remark] =>
    [status] => Array
    (
    [created_at] => Mon Jan 19 11:08:14 +0800 2015
    [id] => 3800682962742684
    [mid] => 3800682962742684
    [idstr] => 3800682962742684
    [text] => 家里日常用品是最好的玩具[嘻嘻]
    [source_allowclick] => 0
    [source_type] => 1
    [source] => <a href="http://app.weibo.com/t/feed/3o33sO" rel="nofollow">iPhone 6</a>
    [favorited] =>
    [truncated] =>
    [in_reply_to_status_id] =>
    [in_reply_to_user_id] =>
    [in_reply_to_screen_name] =>
    [pic_urls] => Array
    (
    )

    [geo] =>
    [reposts_count] => 0
    [comments_count] => 0
    [attitudes_count] => 0
    [isLongText] =>
    [mlevel] => 0
    [visible] => Array
    (
    [type] => 0
    [list_id] => 0
    )

    [biz_feature] => 0
    [hasActionTypeCard] => 0
    [darwin_tags] => Array
    (
    )

    [hot_weibo_tags] => Array
    (
    )

    [text_tag_tips] => Array
    (
    )

    [userType] => 0
    [positive_recom_flag] => 0
    [gif_ids] =>
    [is_show_bulletin] => 0
    )

    [ptype] => 0
    [allow_all_comment] => 1
    [avatar_large] => http://tva3.sinaimg.cn/crop.0.0.180.180.180/69746d43jw1e8qgp5bmzyj2050050aa8.jpg
    [avatar_hd] => http://tva3.sinaimg.cn/crop.0.0.180.180.1024/69746d43jw1e8qgp5bmzyj2050050aa8.jpg
    [verified_reason] =>
    [verified_trade] =>
    [verified_reason_url] =>
    [verified_source] =>
    [verified_source_url] =>
    [follow_me] =>
    [online_status] => 0
    [bi_followers_count] => 67
    [lang] => zh-cn
    [star] => 0
    [mbtype] => 0
    [mbrank] => 0
    [block_word] => 0
    [block_app] => 0
    [credit_score] => 80
    [user_ability] => 0
    [urank] => 12
    )
     */
    public function testGetUserInfo()
    {
        // fix the cli mode lost the "$_SERVER['REMOTE_ADDR']", crash by saetv2.ex.class.php:395
        define('SAE_ACCESSKEY', true);

        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        /** @var WeiboClient $client */
        $client = app(WeiboClient::class);
        $client->setAccessToken('2.00zZXjvB00m8Ic4374c44bfb43suYB');
        $userInfo = $client->show_user_by_id('1769237827');
        $this->assertTrue(isset($userInfo['id']) || (isset($userInfo['error_code']) && $userInfo['error_code'] > 0));
    }

    /**
     * @return Container
     */
    abstract protected function setupApplication();

    /**
     * @param Container $app
     * @return mixed
     */
    abstract protected function setupServiceProvider($app);
}
