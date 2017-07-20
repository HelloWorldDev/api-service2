<?php

namespace Being\Api\Service\Thirdparty;

use Being\Services\App\AppService;
use Being\WeiboOpenApi\WeiboClient;

class AuthWeibo extends Auth
{
    private $appKey;
    private $appSecret;

    public function setConfig($config)
    {
        $this->appKey = $config['weibo']['app_key'];
        $this->appSecret = $config['weibo']['app_secret'];
        return $this;
    }

    public function login($unionid, $code)
    {
        $client = new WeiboClient($this->appKey, $this->appSecret, $code);
        $userInfo = $client->show_user_by_id($unionid);
        AppService::debug('weibo response:' . json_encode($userInfo), __FILE__, __LINE__);
        // {"id":5330652920,"idstr":"5330652920","class":1,"screen_name":"wilson-yuan","name":"wilson-yuan","province":"11","city":"5","location":"\u5317\u4eac \u671d\u9633\u533a","description":"EVERY YOUNTHFUL EVER WEEPING \/ A iOS Developer","url":"","profile_image_url":"http:\/\/tva1.sinaimg.cn\/crop.0.0.749.749.50\/005OKSkUjw8ev6mfcqmnyj30ku0ktdi8.jpg","profile_url":"wilsondev","domain":"wilsondev","weihao":"","gender":"m","followers_count":211,"friends_count":388,"pagefriends_count":1,"statuses_count":1526,"favourites_count":14,"created_at":"Thu Oct 30 23:43:22 +0800 2014","following":false,"allow_all_act_msg":false,"geo_enabled":true,"verified":false,"verified_type":-1,"remark":"","insecurity":{"sexual_content":false},"status":{"created_at":"Tue Jul 18 13:07:38 +0800 2017","id":4130848351029203,"mid":"4130848351029203","idstr":"4130848351029203","text":"\u8f6c\u53d1\u5fae\u535a","source_allowclick":0,"source_type":1,"source":"<a href=\"http:\/\/app.weibo.com\/t\/feed\/6vtZb0\" rel=\"nofollow\">\u5fae\u535a weibo.com<\/a>","favorited":false,"truncated":false,"in_reply_to_status_id":"","in_reply_to_user_id":"","in_reply_to_screen_name":"","pic_urls":[],"geo":null,"reposts_count":0,"comments_count":0,"attitudes_count":0,"isLongText":false,"mlevel":0,"visible":{"type":0,"list_id":0},"biz_feature":0,"hasActionTypeCard":0,"darwin_tags":[],"hot_weibo_tags":[],"text_tag_tips":[],"userType":0,"positive_recom_flag":0,"gif_ids":"","is_show_bulletin":2,"comment_manage_info":{"comment_permission_type":-1}},"ptype":0,"allow_all_comment":true,"avatar_large":"http:\/\/tva1.sinaimg.cn\/crop.0.0.749.749.180\/005OKSkUjw8ev6mfcqmnyj30ku0ktdi8.jpg","avatar_hd":"http:\/\/tva1.sinaimg.cn\/crop.0.0.749.749.1024\/005OKSkUjw8ev6mfcqmnyj30ku0ktdi8.jpg","verified_reason":"","verified_trade":"","verified_reason_url":"","verified_source":"","verified_source_url":"","follow_me":false,"online_status":0,"bi_followers_count":70,"lang":"zh-cn","star":0,"mbtype":0,"mbrank":0,"block_word":0,"block_app":0,"credit_score":80,"user_ability":0,"urank":24,"story_read_state":-1}
        if (!isset($userInfo['id'])) {
            return null;
        }
        $nickname = empty($userInfo['name']) ? '' : $userInfo['name'];
        $avatar = empty($userInfo['avatar_large']) ? '' : $userInfo['avatar_large'];

        return ['unionid' => $unionid, 'code' => $code, 'nickname' => $nickname, 'avatar' => $avatar];
    }
}
